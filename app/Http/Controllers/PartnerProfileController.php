<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartnerProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load('partner');

        // All transactions tied to this partner company
        $transactions = \App\Models\Transaction::query()
            ->where('partner_id', $user->partner_id)
            ->with(['details.service'])
            ->latest()
            ->get();

        $totalOrders  = $transactions->count();
        $totalSpent   = $transactions->sum('total_price');
        $activeOrders = $transactions->whereNotIn('laundry_status', ['Completed'])->count();

        return view('partner.profile', compact(
            'user',
            'transactions',
            'totalOrders',
            'totalSpent',
            'activeOrders'
        ));
    }
}
