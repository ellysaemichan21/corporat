<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\ServiceTier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create()
    {
        $services = \App\Models\Service::query()
            ->with('tier')
            ->orderBy('service_tier_id')
            ->orderBy('name')
            ->get();

        $partners = \App\Models\Partner::orderBy('name')->get(['id', 'name']);
        $userCustomer = null;
        if (auth()->check() && !auth()->user()->isPartner()) {
            $userCustomer = auth()->user()->customer;
        }

        $activePromos = \App\Models\Promo::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->get(['code', 'description', 'value', 'type']);

        return view('public.order.create', [
            'services'     => $services,
            'tiers'        => $services->groupBy(fn (\App\Models\Service $s) => $s->tier?->name ?? 'Other'),
            'partners'     => $partners,
            'userCustomer' => $userCustomer,
            'activePromos' => $activePromos,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'phone'             => ['required', 'string', 'max:50'],
            'delivery_method'   => ['required', 'in:collection,dropoff'],
            'pickup_address'    => ['required_if:delivery_method,collection', 'nullable', 'string', 'max:2000'],
            'expected_datetime' => ['nullable', 'date'],
            'is_priority'       => ['nullable', 'boolean'],
            'is_fast_track'     => ['nullable', 'boolean'],
            'tier_preference'   => ['required', 'in:Essential,Signature,Bespoke'],
            'weight_bundle'     => ['nullable', 'numeric', 'min:1', 'max:1000'],
            'partner_id'        => ['nullable', 'exists:partners,id'],
            'is_corporate'      => ['nullable', 'boolean'],
            'items'             => ['nullable', 'array'],
            'items.*.service_id'=> ['required_with:items', 'exists:services,id'],
            'items.*.qty'       => ['required_with:items', 'numeric', 'min:0'],
            'promo_code'        => ['nullable', 'string', 'max:50'],
        ]);

        // Email is present in the form for UX, but as per schema we omit it here.

        // Retrieve existing customer by phone or user_id, or create a new one
        $customer = null;
        if (auth()->check()) {
            $customer = auth()->user()->customer;
        }

        if (!$customer) {
            $customer = Customer::firstOrCreate(
                ['phone' => $validated['phone']],
                [
                    'name' => $validated['name'],
                    'address' => $validated['pickup_address'] ?? $validated['name'],
                    'tier_preference' => $validated['tier_preference'],
                    'user_id' => auth()->id(), // Link if logged in
                ]
            );
        }

        // Update existing customer info
        $customer->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['pickup_address'] ?? $customer->address,
            'tier_preference' => $validated['tier_preference'],
            'user_id' => $customer->user_id ?? auth()->id(), // Ensure link exists
        ]);

        // Generate a unique Invoice Code
        do {
            $invoiceCode = 'INV-' . strtoupper(Str::random(6));
        } while (Transaction::where('invoice_code', $invoiceCode)->exists());

        $isPartner = auth()->check() && auth()->user()->isPartner();
        $isCorporate = $isPartner || $request->boolean('is_corporate');
        $partnerId = $isPartner ? auth()->user()->partner_id : ($validated['partner_id'] ?? null);

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'customer_name' => $validated['name'], // Snapshot of the name used for this specific order
            'user_id' => auth()->id(), // Link to portal user
            'partner_id' => $partnerId,
            'is_corporate' => $isCorporate,
            'invoice_code' => $invoiceCode,
            'order_channel' => 'Online',
            'tier_level' => $validated['tier_preference'],
            'laundry_status' => ($validated['delivery_method'] === 'collection') ? 'Pending Pickup' : 'Received & Sorted',
            'payment_status' => 'Paid',
            'total_price' => 0,
            'delivery_method' => $validated['delivery_method'],
            'pickup_address' => $validated['pickup_address'],
            'expected_datetime' => $request->has('is_priority') ? now() : $validated['expected_datetime'],
            'is_priority' => $request->has('is_priority'),
            'is_fast_track' => $request->has('is_fast_track'),
        ]);

        // Apply promo code if provided (personal orders only)
        $promoCode = $validated['promo_code'] ?? null;
        if ($promoCode && !$isCorporate) {
            $promo = \App\Models\Promo::where('code', strtoupper($promoCode))
                ->where('is_active', true)
                ->first();
            if ($promo) {
                $transaction->promo_id = $promo->id;
                $transaction->saveQuietly();
            }
        }

        // MANIFEST LOGIC:
        // Prioritize manual items if provided, otherwise fallback to curated bundle logic.
        $items = collect($request->input('items', []))->filter(fn($i) => ($i['qty'] ?? 0) > 0);

        if ($items->isNotEmpty()) {
            foreach ($items as $item) {
                $service = \App\Models\Service::find($item['service_id']);
                if ($service) {
                    $transaction->details()->create([
                        'service_id' => $service->id,
                        'weight'     => (float)$item['qty'],
                        'unit_price' => $service->price,
                    ]);
                }
            }
        } else {
            // BUNDLE LOGIC (Fallback):
            $tier = ServiceTier::where('name', $validated['tier_preference'])->with('services')->first();
            $bulkKg = (float) ($validated['weight_bundle'] ?? 0);

            if ($tier && $tier->services->count() > 0) {
                $services = $tier->services;

                if ($isCorporate && $bulkKg > 0) {
                    $primary   = $services->get(0);
                    $secondary = $services->get(1);

                    if ($primary) {
                        $transaction->details()->create(['service_id' => $primary->id, 'weight' => round($bulkKg * 0.85, 1), 'unit_price' => $primary->price]);
                    }
                    if ($secondary) {
                        $transaction->details()->create(['service_id' => $secondary->id, 'weight' => round($bulkKg * 0.15, 1), 'unit_price' => $secondary->price]);
                    }
                } elseif ($validated['tier_preference'] === 'Essential') {
                    $transaction->details()->create(['service_id' => $services->get(0)->id, 'weight' => 2.5, 'unit_price' => $services->get(0)->price]);
                    $transaction->details()->create(['service_id' => $services->get(1)->id, 'weight' => 1.0, 'unit_price' => $services->get(1)->price]);
                } elseif ($validated['tier_preference'] === 'Signature') {
                    $personalQty = $bulkKg > 0 ? (int) round($bulkKg) : 7;
                    $qty1 = (int) max(1, round($personalQty * 0.6));
                    $qty2 = (int) max(1, $personalQty - $qty1);
                    $transaction->details()->create(['service_id' => $services->get(0)->id, 'weight' => $qty1, 'unit_price' => $services->get(0)->price]);
                    $transaction->details()->create(['service_id' => $services->get(1)->id, 'weight' => $qty2, 'unit_price' => $services->get(1)->price]);
                } elseif ($validated['tier_preference'] === 'Bespoke') {
                    $personalQty = $bulkKg > 0 ? (int) round($bulkKg) : 15;
                    $qty1 = (int) max(1, round($personalQty * 0.6));
                    $qty2 = (int) max(1, $personalQty - $qty1);
                    $transaction->details()->create(['service_id' => $services->get(0)->id, 'weight' => $qty1, 'unit_price' => $services->get(0)->price]);
                    $transaction->details()->create(['service_id' => $services->get(2) ? $services->get(2)->id : $services->get(0)->id, 'weight' => $qty2, 'unit_price' => ($services->get(2) ?? $services->get(0))->price]);
                }
            }
        }

        $transaction->syncTotal();

        // Redirect: logged-in users go directly to their tracking page
        // Guests go to the generic success/confirmation page
        if (auth()->check()) {
            return redirect()->route('portal.track', $transaction->id)
                ->with('success', 'Your order has been initialized. Track your collection below.');
        }

        return redirect()->route('public.order.success', ['invoiceCode' => $invoiceCode]);
    }

    public function portalTrack($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        
        $collectionStatuses = [
            'Pending Pickup', 
            'Sorting & Inspection', 
            'Artisanal Wash', 
            'Professional Pressing', 
            'Final Packaging', 
            'Completed'
        ];

        $dropoffStatuses = [
            'Pending Drop-off', 
            'Received & Sorted', 
            'Deep Cleaning', 
            'Artisanal Pressing', 
            'Completed'
        ];

        $statuses = ($transaction->delivery_method === 'collection') ? $collectionStatuses : $dropoffStatuses;
        $hasReview = $transaction->review()->exists();

        return view('portal.track', compact('transaction', 'statuses', 'hasReview'));
    }

    public function advanceStatus($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $collectionStatuses = [
            'Pending Pickup', 
            'Sorting & Inspection', 
            'Artisanal Wash', 
            'Professional Pressing', 
            'Final Packaging', 
            'Completed'
        ];

        $dropoffStatuses = [
            'Pending Drop-off', 
            'Received & Sorted', 
            'Deep Cleaning', 
            'Artisanal Pressing', 
            'Completed'
        ];

        $chain = ($transaction->delivery_method === 'collection') ? $collectionStatuses : $dropoffStatuses;
        
        $currentStatus = $transaction->laundry_status;
        $currentIndex = array_search($currentStatus, $chain);

        if ($currentIndex !== false && $currentIndex < count($chain) - 1) {
            $newStatus = $chain[$currentIndex + 1];
            $transaction->update(['laundry_status' => $newStatus]);
        }

        return response()->json([
            'status' => $transaction->laundry_status,
            'is_completed' => $transaction->laundry_status === 'Completed'
        ]);
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => ['required', 'exists:transactions,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $transaction = Transaction::findOrFail($validated['transaction_id']);

        \App\Models\Review::create([
            'transaction_id' => $transaction->id,
            'customer_id' => $transaction->customer_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Thank you for your artisanal feedback!');
    }

    public function success($invoiceCode)
    {
        // Simple check to ensure the code exists
        if (!Transaction::where('invoice_code', $invoiceCode)->exists()) {
            abort(404);
        }

        return view('public.order.success', [
            'invoiceCode' => $invoiceCode,
        ]);
    }
}
