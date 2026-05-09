@extends('layouts.storefront')

@section('content')
<div class="py-24 min-h-screen relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-900/10 rounded-full blur-[140px]"></div>
    </div>

    <div class="max-w-5xl mx-auto px-6 lg:px-8 relative z-20">

        {{-- ─── Header ─────────────────────────────────────────────────────── --}}
        <div class="mb-12 scroll-animate">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full border border-blue-500/40 bg-blue-500/10">
                            <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">Corporate Partner</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full border border-emerald-500/40 bg-emerald-500/10">
                            <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">15% B2B Discount Active</span>
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-white">{{ $user->partner->name }}</h1>
                    <p class="text-zinc-400 mt-2">Logged in as <span class="text-zinc-200 font-medium">{{ $user->name }}</span> · {{ $user->email }}</p>
                </div>
                <a href="{{ url('/order') }}" class="luxury-bg text-zinc-950 px-6 py-3 rounded-sm text-sm font-bold tracking-wide uppercase shadow-[0_0_15px_rgba(212,175,55,0.2)] hover:scale-105 transition-transform self-start">
                    + New Order
                </a>
            </div>

            {{-- Contract Start --}}
            @if($user->partner->contract_start_date)
            <p class="text-zinc-500 text-xs mt-4 uppercase tracking-wider">
                Partner since {{ \Carbon\Carbon::parse($user->partner->contract_start_date)->format('F Y') }}
                &nbsp;·&nbsp; {{ $user->partner->address }}
            </p>
            @endif
        </div>

        {{-- ─── Stats ───────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12 scroll-animate delay-100">
            <div class="card-glass rounded-xl p-6 border-zinc-800">
                <p class="text-zinc-500 text-xs uppercase tracking-widest mb-2">Total Orders</p>
                <p class="text-3xl font-serif font-bold text-white">{{ $totalOrders }}</p>
            </div>
            <div class="card-glass rounded-xl p-6 border-zinc-800">
                <p class="text-zinc-500 text-xs uppercase tracking-widest mb-2">Active Orders</p>
                <p class="text-3xl font-serif font-bold text-amber-400">{{ $activeOrders }}</p>
            </div>
            <div class="card-glass rounded-xl p-6 border-zinc-800">
                <p class="text-zinc-500 text-xs uppercase tracking-widest mb-2">Total Spent</p>
                <p class="text-3xl font-serif font-bold text-white">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- ─── Promo Banner ────────────────────────────────────────────────── --}}
        <div class="card-glass rounded-xl p-5 border border-blue-500/20 bg-blue-500/5 mb-12 scroll-animate delay-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 9V4a1 1 0 011-1z"/>
                </svg>
            </div>
            <div>
                <p class="text-blue-300 font-bold text-sm">Promo Code: <span class="font-mono bg-blue-900/30 px-2 py-0.5 rounded text-blue-200">PARTNER15</span></p>
                <p class="text-zinc-400 text-xs mt-0.5">Your 15% B2B corporate discount is applied automatically on every order while logged in.</p>
            </div>
        </div>

        {{-- ─── Order History ────────────────────────────────────────────────── --}}
        <div class="scroll-animate delay-300">
            <h2 class="text-2xl font-serif font-bold text-white mb-6 flex items-center gap-3">
                <span class="w-8 h-px bg-amber-500/50"></span>
                Order History
            </h2>

            @if($transactions->isEmpty())
                <div class="card-glass rounded-xl p-16 border-zinc-800 text-center">
                    <p class="text-zinc-500 text-lg mb-2">No orders yet</p>
                    <p class="text-zinc-600 text-sm">Place your first corporate order to see it here.</p>
                    <a href="{{ url('/order') }}" class="inline-block mt-6 luxury-bg text-zinc-950 px-6 py-2.5 rounded-sm text-sm font-bold uppercase tracking-wide">Book Service</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($transactions as $txn)
                    <div class="card-glass rounded-xl p-6 border-zinc-800 hover:border-zinc-700 transition-colors">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-white font-mono font-bold">{{ $txn->invoice_code }}</span>
                                    {{-- Laundry Status Badge --}}
                                    @php
                                        $statusColor = match($txn->laundry_status) {
                                            'Pending'             => 'zinc',
                                            'Sorting & QC'        => 'purple',
                                            'Washing'             => 'blue',
                                            'Drying'              => 'sky',
                                            'Ironing'             => 'orange',
                                            'Ready for Dispatch'  => 'amber',
                                            'Completed'           => 'emerald',
                                            default               => 'zinc',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest
                                        bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400 border border-{{ $statusColor }}-500/30">
                                        {{ $txn->laundry_status }}
                                    </span>
                                    {{-- Payment Badge --}}
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest
                                        {{ $txn->payment_status === 'Paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                                        {{ $txn->payment_status }}
                                    </span>
                                </div>
                                <p class="text-zinc-500 text-xs">{{ $txn->created_at->format('d M Y, H:i') }} &nbsp;·&nbsp; {{ $txn->tier_level }} Tier</p>
                                @if($txn->details->isNotEmpty())
                                <p class="text-zinc-400 text-xs mt-1">
                                    {{ $txn->details->map(fn($d) => $d->service?->name . ' ×' . number_format($d->qty, 1))->join(', ') }}
                                </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-white font-bold text-lg">Rp {{ number_format($txn->total_price, 0, ',', '.') }}</p>
                                <a href="{{ route('public.track.show', $txn->invoice_code) }}" class="text-amber-500 hover:text-amber-400 text-xs uppercase tracking-wider font-bold transition-colors">
                                    Track →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
