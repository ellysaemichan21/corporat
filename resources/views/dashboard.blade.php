@extends('layouts.storefront')

@section('content')
<div class="py-24 min-h-screen relative overflow-hidden">
    <!-- Ambient Background Elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-0 w-[600px] h-[600px] bg-amber-900/5 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-blue-900/5 rounded-full blur-[130px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12 scroll-animate">
            <div>
                <h1 class="text-4xl font-serif font-bold text-white mb-2">{{ __('Client Portal') }}</h1>
                <p class="text-zinc-500 tracking-wide">{{ __('Manage your artisanal garment care and track your collection status.') }}</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('public.order.create') }}" class="luxury-bg text-zinc-950 px-6 py-3 rounded-sm text-sm font-bold tracking-widest uppercase shadow-[0_0_20px_rgba(212,175,55,0.2)] transition-transform hover:scale-105 active:scale-95">
                    {{ __('New Order') }}
                </a>
            </div>
        </div>

        {{-- ─── ACTIVE JOURNEYS ─────────────────────────────────────────── --}}
        <div class="mb-16 scroll-animate delay-100">
            <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-amber-500 mb-6 flex items-center gap-4">
                {{ __('Active Journeys') }}
                <span class="h-px flex-grow bg-amber-500/20"></span>
            </h3>

            @if($activeTransactions->isEmpty())
                <div class="card-glass rounded-xl p-16 text-center border-zinc-800">
                    <div class="w-16 h-16 bg-zinc-900 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-serif text-white mb-2">{{ __('No Active Collections') }}</h4>
                    <p class="text-zinc-500 mb-8">{{ __('Your garments are currently in your care. Start a new journey whenever you are ready.') }}</p>
                    <a href="{{ route('public.order.create') }}" class="inline-block border border-amber-500/30 text-amber-500 px-8 py-3 rounded-sm text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-amber-500/10 transition-all">
                        {{ __('Initiate Collection') }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activeTransactions as $tx)
                        <div class="card-glass rounded-xl border-zinc-800 hover:border-amber-500/30 transition-all group overflow-hidden">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex items-center gap-2 px-2 py-1 bg-amber-500/10 border border-amber-500/20 rounded-sm">
                                        <div class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></div>
                                        <span class="text-[9px] text-amber-500 font-bold uppercase tracking-widest">{{ $tx->laundry_status }}</span>
                                    </div>
                                    <span class="text-[10px] font-mono text-zinc-500">{{ $tx->invoice_code }}</span>
                                </div>
                                
                                <h4 class="text-lg font-serif font-bold text-white mb-1">{{ $tx->customer_name }}</h4>
                                <p class="text-xs text-zinc-500 mb-6">{{ $tx->created_at->format('M d, Y • H:i') }}</p>
                                
                                <div class="flex items-center justify-between pt-6 border-t border-zinc-800">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-zinc-600 uppercase tracking-widest mb-1">{{ __('Total Est.') }}</span>
                                        <span class="text-sm font-mono text-zinc-300">Rp {{ number_format($tx->total_price, 0, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ route('portal.track', $tx->id) }}" class="flex items-center gap-2 text-[10px] font-bold text-amber-500 uppercase tracking-widest group-hover:gap-3 transition-all">
                                        {{ __('Track Status') }}
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ─── ARCHIVE ─────────────────────────────────────────────────── --}}
        <div>
            <div class="scroll-animate delay-200">
                <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-zinc-600 mb-6 flex items-center gap-4">
                    {{ __('Artisanal Archive') }}
                    <span class="h-px flex-grow bg-zinc-800"></span>
                </h3>

                <div class="card-glass rounded-xl overflow-hidden border-zinc-800">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-zinc-900/50 border-b border-zinc-800">
                                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">{{ __('Date') }}</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">{{ __('Invoice') }}</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">{{ __('Customer') }}</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">{{ __('Total') }}</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50">
                            @forelse($archivedTransactions as $tx)
                                <tr class="hover:bg-zinc-900/20 transition-colors">
                                    <td class="px-6 py-4 text-xs text-zinc-400">{{ $tx->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-xs font-mono text-zinc-500">{{ $tx->invoice_code }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-white">{{ $tx->customer_name }}</td>
                                    <td class="px-6 py-4 text-xs font-mono text-zinc-400">Rp {{ number_format($tx->total_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('portal.track', $tx->id) }}" class="text-[9px] font-bold text-zinc-500 hover:text-amber-500 uppercase tracking-widest transition-colors">
                                            {{ __('Details') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-zinc-600 text-xs italic tracking-widest">
                                        {{ __('No archived masterpieces found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
