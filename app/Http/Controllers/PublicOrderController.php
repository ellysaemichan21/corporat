<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicOrderController extends Controller
{
    public function create(Request $request)
    {
        $channel = $request->route('channel') ?? $request->string('channel')->toString() ?? 'Self';
        $channel = in_array($channel, ['Self', 'Online'], true) ? $channel : 'Self';

        $services = Service::query()
            ->with('tier')
            ->orderBy('service_tier_id')
            ->orderBy('name')
            ->get();

        $partners = Partner::orderBy('name')->get(['id', 'name']);

        return view('public.order.create', [
            'services' => $services,
            'tiers'    => $services->groupBy(fn (Service $s) => $s->tier?->name ?? 'Other'),
            'channel'  => $channel,
            'partners' => $partners,
        ]);
    }

    public function store(Request $request)
    {
        $isCorporate = $request->boolean('is_corporate');

        $validated = $request->validate([
            'is_corporate'     => ['boolean'],
            'partner_id'       => $isCorporate ? ['required', 'exists:partners,id'] : ['nullable'],
            'order_channel'    => ['required', 'in:Self,Online'],
            'customer.name'    => ['required', 'string', 'max:255'],
            'customer.phone'   => ['required', 'string', 'max:50'],
            'customer.address' => ['nullable', 'string', 'max:2000'],
            'tier_level'       => ['required', 'in:Essential,Signature,Bespoke'],
            'pickup_note'      => ['nullable', 'string', 'max:255'],
            'items'            => ['array'],
            'items.*.service_id' => ['required', 'integer', 'exists:services,id'],
            'items.*.qty'        => ['nullable', 'numeric', 'min:0'],
        ]);

        // Corporate orders CANNOT use Essential tier
        if ($isCorporate && $validated['tier_level'] === 'Essential') {
            return back()
                ->withInput()
                ->withErrors(['tier_level' => 'Corporate orders require Signature or Bespoke tier. Essential tier is not available for bulk orders.']);
        }

        if (($validated['order_channel'] ?? 'Self') === 'Online' && blank($validated['customer']['address'] ?? null)) {
            return back()
                ->withInput()
                ->withErrors(['customer.address' => 'Address is required for online order (pickup/delivery).']);
        }

        $rawItems = collect($validated['items'] ?? [])
            ->map(fn (array $row) => [
                'service_id' => (int) $row['service_id'],
                'qty'        => (float) ($row['qty'] ?? 0),
            ])
            ->filter(fn (array $row) => $row['qty'] > 0)
            ->values();

        if ($rawItems->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['items' => 'Pick at least one service and set qty > 0.']);
        }

        $invoiceCode = $this->generateInvoiceCode();

        $transaction = DB::transaction(function () use ($validated, $rawItems, $invoiceCode, $isCorporate) {
            $customer = Customer::query()
                ->firstOrCreate(
                    ['phone' => $validated['customer']['phone']],
                    [
                        'name'            => $validated['customer']['name'],
                        'address'         => $validated['customer']['address'] ?? null,
                        'tier_preference' => $validated['tier_level'],
                    ],
                );

            $customer->fill([
                'name'            => $validated['customer']['name'],
                'address'         => $validated['customer']['address'] ?? $customer->address,
                'tier_preference' => $validated['tier_level'],
            ])->save();

            $services = Service::query()
                ->whereIn('id', $rawItems->pluck('service_id')->all())
                ->get()
                ->keyBy('id');

            $total = 0.0;

            $transaction = Transaction::query()->create([
                'customer_id'   => $customer->id,
                'invoice_code'  => $invoiceCode,
                'order_channel' => $validated['order_channel'],
                'pickup_note'   => $validated['pickup_note'] ?? null,
                'tier_level'    => $validated['tier_level'],
                'is_corporate'  => $isCorporate,
                'partner_id'    => $isCorporate ? ($validated['partner_id'] ?? null) : null,
                'laundry_status' => 'Pending',
                'payment_status' => 'Unpaid',
                'total_price'    => 0,
            ]);

            foreach ($rawItems as $row) {
                $service = $services->get($row['service_id']);
                if (!$service) continue;

                $qty      = (float) $row['qty'];
                $subtotal = round($qty * (float) $service->price, 2);
                $total   += $subtotal;

                TransactionDetail::query()->create([
                    'transaction_id' => $transaction->id,
                    'service_id'     => $service->id,
                    'qty'            => $qty,
                    'subtotal'       => $subtotal,
                ]);
            }

            $transaction->update(['total_price' => round($total, 2)]);

            // ── Auto-apply 15% B2B discount for logged-in partner users ──────
            if (auth()->check() && auth()->user()->isPartner()) {
                $discounted = round($total * 0.85, 2); // 15% off
                $transaction->update(['total_price' => $discounted]);
            }

            return $transaction->fresh(['customer', 'details.service']);
        });

        return redirect()->route('public.order.success', ['invoiceCode' => $transaction->invoice_code]);
    }

    public function success(string $invoiceCode)
    {
        $transaction = Transaction::query()
            ->where('invoice_code', $invoiceCode)
            ->with(['customer', 'partner', 'details.service'])
            ->firstOrFail();

        return view('public.order.success', [
            'transaction' => $transaction,
        ]);
    }

    public function trackForm()
    {
        return view('public.track.form');
    }

    public function trackShow(string $invoiceCode)
    {
        $transaction = Transaction::query()
            ->where('invoice_code', $invoiceCode)
            ->with(['customer', 'partner', 'details.service', 'images', 'logs'])
            ->firstOrFail();

        return view('public.track.show', [
            'transaction' => $transaction,
        ]);
    }

    private function generateInvoiceCode(): string
    {
        do {
            $code = 'INV-' . strtoupper(Str::random(6));
        } while (Transaction::query()->where('invoice_code', $code)->exists());

        return $code;
    }
}
