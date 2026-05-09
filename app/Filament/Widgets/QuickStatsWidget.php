<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Partner;
use App\Models\Transaction;
use Filament\Widgets\Widget;

class QuickStatsWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-stats';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    public function getData(): array
    {
        $recentOrders = Transaction::with('customer')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $pendingOrders = Transaction::whereIn('laundry_status', ['Pending', 'Processing', 'Ready'])
            ->count();

        $totalRevenue = Transaction::where('total_price', '>', 0)->sum('total_price');

        $corporateOrders = Transaction::where('is_corporate', true)->count();

        return [
            'recent'       => $recentOrders,
            'pending'      => $pendingOrders,
            'revenue'      => $totalRevenue,
            'corporate'    => $corporateOrders,
            'customers'    => Customer::count(),
            'partners'     => Partner::count(),
        ];
    }
}
