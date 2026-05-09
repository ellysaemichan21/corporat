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

        return view('public.order.create', [
            'services'     => $services,
            'tiers'        => $services->groupBy(fn (\App\Models\Service $s) => $s->tier?->name ?? 'Other'),
            'partners'     => $partners,
            'userCustomer' => $userCustomer,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'phone'             => ['required', 'string', 'max:50'],
            'delivery_method'   => ['required', 'in:collection,dropoff'],
            'pickup_address'    => ['required_if:delivery_method,collection', 'nullable', 'string', 'max:2000'],
            'expected_datetime' => ['required_without:is_priority', 'nullable', 'date'],
            'is_priority'       => ['nullable', 'boolean'],
            'is_fast_track'     => ['nullable', 'boolean'],
            'tier_preference'   => ['required', 'in:Essential,Signature,Bespoke'],
            'weight_bundle'     => ['nullable', 'numeric', 'min:1', 'max:1000'],
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
        $partnerId = $isPartner ? auth()->user()->partner_id : null;

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'customer_name' => $validated['name'], // Snapshot of the name used for this specific order
            'user_id' => auth()->id(), // Link to portal user
            'partner_id' => $partnerId,
            'is_corporate' => $isCorporate,
            'invoice_code' => $invoiceCode,
            'order_channel' => 'Online',
            'tier_level' => $validated['tier_preference'],
            'laundry_status' => ($validated['delivery_method'] === 'collection') ? 'Pending Pickup' : 'Pending Drop-off',
            'payment_status' => 'Paid',
            'total_price' => 0,
            'delivery_method' => $validated['delivery_method'],
            'pickup_address' => $validated['pickup_address'],
            'expected_datetime' => $request->has('is_priority') ? now() : $validated['expected_datetime'],
            'is_priority' => $request->has('is_priority'),
            'is_fast_track' => $request->has('is_fast_track'),
        ]);

        // BUNDLE LOGIC:
        // For corporate orders, use the submitted bulk weight (50/150/300 kg).
        // For personal orders, use realistic garment bundle weights.
        $tier = ServiceTier::where('name', $validated['tier_preference'])->with('services')->first();
        $isCorporate = $request->boolean('is_corporate');
        $bulkKg = (float) ($validated['weight_bundle'] ?? 0);

        if ($tier && $tier->services->count() > 0) {
            $services = $tier->services;

            if ($isCorporate && $bulkKg > 0) {
                // Corporate: distribute the bulk kg across available services in the tier
                // Primary service gets 85%, secondary gets 15% (e.g. main wash + finishing)
                $primary   = $services->get(0);
                $secondary = $services->get(1);

                if ($primary) {
                    $transaction->details()->create([
                        'service_id' => $primary->id,
                        'weight'     => round($bulkKg * 0.85, 1),
                        'unit_price' => $primary->price,
                    ]);
                }
                if ($secondary) {
                    $transaction->details()->create([
                        'service_id' => $secondary->id,
                        'weight'     => round($bulkKg * 0.15, 1),
                        'unit_price' => $secondary->price,
                    ]);
                }
            } elseif ($validated['tier_preference'] === 'Essential') {
                // Bundle: 2.5 Kg Wash & Fold + 1 Bed Linen
                $transaction->details()->create(['service_id' => $services->get(0)->id, 'weight' => 2.5, 'unit_price' => $services->get(0)->price]);
                $transaction->details()->create(['service_id' => $services->get(1)->id, 'weight' => 1.0, 'unit_price' => $services->get(1)->price]);
            } elseif ($validated['tier_preference'] === 'Signature') {
                // Personal bundle: use selected kg or default to 7kg
                $personalKg = $bulkKg > 0 ? $bulkKg : 7;
                $transaction->details()->create(['service_id' => $services->get(0)->id, 'weight' => round($personalKg * 0.7, 1), 'unit_price' => $services->get(0)->price]);
                $transaction->details()->create(['service_id' => $services->get(1)->id, 'weight' => round($personalKg * 0.3, 1), 'unit_price' => $services->get(1)->price]);
            } elseif ($validated['tier_preference'] === 'Bespoke') {
                // Personal bundle: use selected kg or default to 15kg
                $personalKg = $bulkKg > 0 ? $bulkKg : 15;
                $transaction->details()->create(['service_id' => $services->get(0)->id, 'weight' => round($personalKg * 0.6, 1), 'unit_price' => $services->get(0)->price]);
                $transaction->details()->create(['service_id' => $services->get(2) ? $services->get(2)->id : $services->get(0)->id, 'weight' => round($personalKg * 0.4, 1), 'unit_price' => ($services->get(2) ?? $services->get(0))->price]);
            }

            $transaction->syncTotal();
        }

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
        $user = auth()->user();
        $transaction = Transaction::findOrFail($id);

        // Security: Ensure user owns the transaction OR belongs to the same corporate partner
        if ($transaction->user_id !== $user->id && (!$user->isPartner() || $transaction->partner_id !== $user->partner_id)) {
            abort(403, 'Unauthorized access to this artisanal journey.');
        }
        
        $collectionStatuses = [
            'Pending Pickup', 
            'Driver En Route', 
            'Received at Facility', 
            'Artisanal Purification', 
            'Outbound Delivery', 
            'Completed'
        ];

        $dropoffStatuses = [
            'Pending Drop-off', 
            'Received at Facility', 
            'Artisanal Purification', 
            'Ready for Client Pickup', 
            'Completed'
        ];

        $statuses = ($transaction->delivery_method === 'collection') ? $collectionStatuses : $dropoffStatuses;

        return view('portal.track', compact('transaction', 'statuses'));
    }

    public function advanceStatus($id)
    {
        $user = auth()->user();
        $transaction = Transaction::findOrFail($id);
        
        // Security: Same ownership check
        if ($transaction->user_id !== $user->id && (!$user->isPartner() || $transaction->partner_id !== $user->partner_id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $collectionStatuses = [
            'Pending Pickup', 
            'Driver En Route', 
            'Received at Facility', 
            'Artisanal Purification', 
            'Outbound Delivery', 
            'Completed'
        ];

        $dropoffStatuses = [
            'Pending Drop-off', 
            'Received at Facility', 
            'Artisanal Purification', 
            'Ready for Client Pickup', 
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
        $user = auth()->user();
        $validated = $request->validate([
            'transaction_id' => ['required', 'exists:transactions,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $transaction = Transaction::findOrFail($validated['transaction_id']);

        // Security: Same ownership check
        if ($transaction->user_id !== $user->id && (!$user->isPartner() || $transaction->partner_id !== $user->partner_id)) {
            abort(403, 'Unauthorized feedback attempt.');
        }

        \App\Models\Review::create([
            'transaction_id' => $transaction->id,
            'customer_id' => $transaction->customer_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('public.landing')->with('success', 'Thank you for your artisanal feedback!');
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
