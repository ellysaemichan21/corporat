@extends('layouts.storefront')

@section('content')
<div class="py-24 bg-zinc-950 min-h-screen relative overflow-hidden">
    <!-- Subtle Background Element -->
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-amber-900/10 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/3"></div>

    <div class="max-w-5xl mx-auto px-6 lg:px-8 relative z-10 scroll-animate">
        <div class="text-center mb-20 scroll-animate delay-100">
            <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6 tracking-tight">{{ __('The Protocol') }}</h1>
            <p class="text-zinc-400 text-lg md:text-xl max-w-2xl mx-auto">
                {{ __('A seamless, uncompromising three-step process designed for maximum discretion and flawless execution.') }}
            </p>
        </div>

        <!-- Vertical Timeline Layout -->
        <div class="relative wrap overflow-hidden p-4 h-full">
            <!-- Center Line -->
            <div class="absolute border-opacity-20 border-zinc-500 h-full border hidden md:block" style="left: 50%"></div>
            
            <!-- Step 1 -->
            <div class="mb-16 flex justify-between items-center w-full md:right-timeline flex-col md:flex-row scroll-animate delay-200">
                <div class="order-1 w-full md:w-5/12"></div>
                <div class="z-20 flex items-center order-1 bg-zinc-900 shadow-xl w-16 h-16 rounded-full border border-amber-500/50 mb-6 md:mb-0">
                    <h1 class="mx-auto font-semibold text-2xl text-amber-500 font-serif">01</h1>
                </div>
                <div class="order-1 card-glass rounded-xl w-full md:w-5/12 px-8 py-8 relative group border-t border-amber-500/20">
                    <h3 class="mb-3 font-bold text-white text-2xl font-serif">{{ __('Schedule Your Drop-off') }}</h3>
                    <p class="text-sm leading-relaxed text-zinc-400">
                        {{ __('Creation of the logistical manifest. Our encrypted portal securely records your location, preferred tier, and special handling requests.') }}
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="mb-16 flex justify-between flex-row-reverse items-center w-full md:left-timeline flex-col md:flex-row scroll-animate delay-300">
                <div class="order-1 w-full md:w-5/12"></div>
                <div class="z-20 flex items-center order-1 bg-zinc-900 shadow-xl w-16 h-16 rounded-full border border-amber-500/50 mb-6 md:mb-0">
                    <h1 class="mx-auto text-white font-semibold text-2xl text-amber-500 font-serif">02</h1>
                </div>
                <div class="order-1 card-glass rounded-xl w-full md:w-5/12 px-8 py-8 relative group border-t border-amber-500/20">
                    <h3 class="mb-3 font-bold text-white text-2xl font-serif">{{ __('Artisanal QC Process') }}</h3>
                    <p class="text-sm leading-relaxed text-zinc-400">
                        {{ __('Mention our unblinking photo-log audit trail. Every garment is meticulously inspected, logged, and treated by certified artisans under our uncompromising quality control protocols.') }}
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex justify-between items-center w-full md:right-timeline flex-col md:flex-row scroll-animate delay-400">
                <div class="order-1 w-full md:w-5/12"></div>
                <div class="z-20 flex items-center order-1 bg-zinc-900 shadow-xl w-16 h-16 rounded-full border border-amber-500/50 mb-6 md:mb-0">
                    <h1 class="mx-auto font-semibold text-2xl text-amber-500 font-serif">03</h1>
                </div>
                <div class="order-1 card-glass rounded-xl w-full md:w-5/12 px-8 py-8 relative group border-t border-amber-500/20">
                    <h3 class="mb-3 font-bold text-white text-2xl font-serif">{{ __('White-Glove Dispatch') }}</h3>
                    <p class="text-sm leading-relaxed text-zinc-400">
                        {{ __('Return of impeccable garments. Delivered directly to your executive concierge or private residence via our climate-controlled private fleet.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-20 text-center scroll-animate delay-500">
            <a href="{{ url('/order') }}" class="inline-block luxury-bg text-zinc-950 px-10 py-4 rounded-sm text-sm font-bold transition-transform hover:scale-105 tracking-widest uppercase shadow-[0_0_20px_rgba(212,175,55,0.2)]">
                {{ __('Initiate Manifest') }}
            </a>
        </div>
    </div>
</div>
@endsection
