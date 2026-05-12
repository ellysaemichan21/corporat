<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Partner;
use App\Models\Transaction;
use Filament\Widgets\Widget;

class HeroBannerWidget extends Widget
{
    protected string $view = 'filament.widgets.hero-banner';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    public function getData(): array
    {
        return [
            'orders'      => Transaction::count(),
            'active'      => Transaction::whereIn('laundry_status', ['Sorting & QC', 'Washing', 'Drying', 'Ironing'])->count(),
            'revenue'     => Transaction::where('total_price', '>', 0)->sum('total_price'),
            'partners'    => Partner::count(),
            'pending'     => Transaction::whereIn('laundry_status', ['Pending', 'Processing', 'Ready'])->count(),
            'customers'   => Customer::count(),
            'corporate'   => Transaction::where('is_corporate', true)->count(),
            'recent'      => Transaction::with(['customer', 'driver', 'sorter', 'washer', 'presser', 'packer'])->orderByDesc('created_at')->limit(5)->get(),
        ];
    }
}
