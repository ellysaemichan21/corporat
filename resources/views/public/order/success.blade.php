@extends('layouts.storefront')

@section('content')
<div class="py-24 min-h-screen relative overflow-hidden flex items-center justify-center">
    <!-- Subtle background elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber-900/10 rounded-full blur-[150px]"></div>
    </div>

    <div class="max-w-2xl mx-auto px-6 lg:px-8 relative z-20 w-full">
        <div class="card-glass rounded-xl p-10 md:p-16 relative overflow-hidden border-zinc-800 shadow-2xl text-center">
            <!-- Decorative line -->
            <div class="absolute top-0 inset-x-0 h-1 luxury-bg"></div>
            
            <div class="w-20 h-20 mx-auto rounded-full bg-zinc-900 border border-amber-500/30 flex items-center justify-center mb-8 text-amber-500 shadow-[0_0_30px_rgba(212,175,55,0.15)]">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">{{ __('Manifest Secured') }}</h1>
            
            <p class="text-zinc-400 text-lg mb-8 max-w-md mx-auto">
                {{ __('Your service request has been logged. Please securely bag your garments and provide this reference code to your concierge.') }}
            </p>
 
            <div class="bg-zinc-900/80 border border-zinc-800 rounded-lg p-6 mb-10 inline-block w-full max-w-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">{{ __('Invoice Code') }}</p>
                <p class="text-3xl font-mono font-bold text-amber-500 tracking-wider">{{ $invoiceCode }}</p>
            </div>
 
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.landing') }}" class="px-8 py-3 rounded-sm border border-zinc-700 text-zinc-300 hover:border-amber-500/50 hover:text-amber-400 text-sm font-bold transition-all tracking-wider uppercase">
                    {{ __('Return to Home') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
