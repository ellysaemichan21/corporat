<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create()
    {
        return view('public.order.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:2000'],
            'tier_preference' => ['required', 'in:Essential,Signature,Bespoke'],
        ]);

        // Email is present in the form for UX, but as per schema we omit it here.

        // Retrieve existing customer by phone or create a new one
        $customer = Customer::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['name'],
                'address' => $validated['address'],
                'tier_preference' => $validated['tier_preference'],
            ]
        );

        // Update existing customer info just in case they moved or changed preference
        $customer->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'tier_preference' => $validated['tier_preference'],
        ]);

        // Generate a unique Invoice Code
        do {
            $invoiceCode = 'INV-' . strtoupper(Str::random(6));
        } while (Transaction::where('invoice_code', $invoiceCode)->exists());

        // Create a new record in transactions table
        Transaction::create([
            'customer_id' => $customer->id,
            'invoice_code' => $invoiceCode,
            'order_channel' => 'Online',
            'tier_level' => $validated['tier_preference'],
            'laundry_status' => 'Pending',
            'payment_status' => 'Unpaid',
            'total_price' => 0,
        ]);

        // Redirect to a beautiful "Success" page with their Invoice Code
        return redirect()->route('public.order.success', ['invoiceCode' => $invoiceCode]);
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
