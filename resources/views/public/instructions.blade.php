@extends('layouts.storefront')

@section('content')
<div class="py-24 min-h-screen relative overflow-hidden">
    <!-- Subtle Background Element -->
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-amber-900/10 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/3 z-0"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-900/5 rounded-full blur-[120px] translate-y-1/3 -translate-x-1/4 z-0"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-20">
        
        <!-- Majestic Hero Header -->
        <div class="text-center mb-32 scroll-animate">
            <div class="inline-flex items-center justify-center gap-4 mb-6">
                <div class="w-12 h-px bg-amber-500/50"></div>
                <span class="text-amber-500 uppercase tracking-[0.2em] text-sm font-semibold">{{ __('Our Journey') }}</span>
                <div class="w-12 h-px bg-amber-500/50"></div>
            </div>
            <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6 tracking-tight drop-shadow-lg">
                {{ __('The Bespoke Process:') }}<br/>
                <span class="text-3xl md:text-5xl text-zinc-300 italic">{{ __('Six Stages of Uncompromising Care.') }}</span>
            </h1>
        </div>

        <!-- The 6 Stages (Zigzag Layout) -->
        <div class="space-y-32">
            
            <!-- Step 1 -->
            <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-20 scroll-animate">
                <div class="w-full md:w-1/2 relative group">
                    <div class="absolute inset-0 bg-amber-500/20 blur-[80px] opacity-0 group-hover:opacity-100 transition duration-700 z-0"></div>
                    <img src="{{ asset('test/images/1s.png') }}" alt="The Initial Dispatch" class="w-full aspect-[4/3] object-cover rounded-xl shadow-[0_0_40px_rgba(0,0,0,0.8)] relative z-10 border border-zinc-800/80 transition-transform hover:scale-[1.02] duration-700">
                </div>
                <div class="w-full md:w-1/2 space-y-6">
                    <div class="flex items-center gap-6 mb-2">
                        <span class="text-amber-500 font-serif text-6xl opacity-30 font-bold">01</span>
                        <div class="h-px bg-zinc-800 flex-grow"></div>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-serif text-white font-bold">{{ __('The Initial Dispatch') }}</h3>
                    <p class="text-zinc-400 text-lg md:text-xl leading-relaxed">
                        {{ __('Upon securing your order, our white-glove logistics team arrives at your location to collect your garments with absolute discretion.') }}
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col md:flex-row-reverse items-center gap-12 lg:gap-20 scroll-animate">
                <div class="w-full md:w-1/2 relative group">
                    <div class="absolute inset-0 bg-blue-500/10 blur-[80px] opacity-0 group-hover:opacity-100 transition duration-700 z-0"></div>
                    <img src="{{ asset('test/images/2s.png') }}" alt="Secure Chain-of-Custody" class="w-full aspect-[4/3] object-cover rounded-xl shadow-[0_0_40px_rgba(0,0,0,0.8)] relative z-10 border border-zinc-800/80 transition-transform hover:scale-[1.02] duration-700">
                </div>
                <div class="w-full md:w-1/2 space-y-6">
                    <div class="flex items-center gap-6 mb-2 flex-row-reverse">
                        <span class="text-amber-500 font-serif text-6xl opacity-30 font-bold">02</span>
                        <div class="h-px bg-zinc-800 flex-grow"></div>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-serif text-white font-bold md:text-right">{{ __('Secure Chain-of-Custody') }}</h3>
                    <p class="text-zinc-400 text-lg md:text-xl leading-relaxed md:text-right">
                        {{ __('Your collection is transferred to our flagship facility, immediately entering our tracked and secure intake system.') }}
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-20 scroll-animate">
                <div class="w-full md:w-1/2 relative group">
                    <div class="absolute inset-0 bg-amber-500/20 blur-[80px] opacity-0 group-hover:opacity-100 transition duration-700 z-0"></div>
                    <img src="{{ asset('test/images/3s.png') }}" alt="Inspection & Purification" class="w-full aspect-[4/3] object-cover rounded-xl shadow-[0_0_40px_rgba(0,0,0,0.8)] relative z-10 border border-zinc-800/80 transition-transform hover:scale-[1.02] duration-700">
                </div>
                <div class="w-full md:w-1/2 space-y-6">
                    <div class="flex items-center gap-6 mb-2">
                        <span class="text-amber-500 font-serif text-6xl opacity-30 font-bold">03</span>
                        <div class="h-px bg-zinc-800 flex-grow"></div>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-serif text-white font-bold">{{ __('Inspection & Purification') }}</h3>
                    <p class="text-zinc-400 text-lg md:text-xl leading-relaxed">
                        {{ __('Every garment undergoes rigorous material analysis before our master cleaners treat your collection utilizing premium, fabric-specific solvents.') }}
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col md:flex-row-reverse items-center gap-12 lg:gap-20 scroll-animate">
                <div class="w-full md:w-1/2 relative group">
                    <div class="absolute inset-0 bg-zinc-500/20 blur-[80px] opacity-0 group-hover:opacity-100 transition duration-700 z-0"></div>
                    <img src="{{ asset('test/images/4s.png') }}" alt="Structural Pressing" class="w-full aspect-[4/3] object-cover rounded-xl shadow-[0_0_40px_rgba(0,0,0,0.8)] relative z-10 border border-zinc-800/80 transition-transform hover:scale-[1.02] duration-700">
                </div>
                <div class="w-full md:w-1/2 space-y-6">
                    <div class="flex items-center gap-6 mb-2 flex-row-reverse">
                        <span class="text-amber-500 font-serif text-6xl opacity-30 font-bold">04</span>
                        <div class="h-px bg-zinc-800 flex-grow"></div>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-serif text-white font-bold md:text-right">{{ __('Structural Pressing') }}</h3>
                    <p class="text-zinc-400 text-lg md:text-xl leading-relaxed md:text-right">
                        {{ __('Garments are meticulously hand-pressed and precision-steamed to restore their original architectural silhouette.') }}
                    </p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-20 scroll-animate">
                <div class="w-full md:w-1/2 relative group">
                    <div class="absolute inset-0 bg-amber-500/20 blur-[80px] opacity-0 group-hover:opacity-100 transition duration-700 z-0"></div>
                    <img src="{{ asset('test/images/5s.png') }}" alt="Outbound Sealing" class="w-full aspect-[4/3] object-cover rounded-xl shadow-[0_0_40px_rgba(0,0,0,0.8)] relative z-10 border border-zinc-800/80 transition-transform hover:scale-[1.02] duration-700">
                </div>
                <div class="w-full md:w-1/2 space-y-6">
                    <div class="flex items-center gap-6 mb-2">
                        <span class="text-amber-500 font-serif text-6xl opacity-30 font-bold">05</span>
                        <div class="h-px bg-zinc-800 flex-grow"></div>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-serif text-white font-bold">{{ __('Outbound Sealing') }}</h3>
                    <p class="text-zinc-400 text-lg md:text-xl leading-relaxed">
                        {{ __('Following a final, uncompromising quality assurance check, your items are sealed in climate-protective, bespoke packaging.') }}
                    </p>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="flex flex-col md:flex-row-reverse items-center gap-12 lg:gap-20 scroll-animate">
                <div class="w-full md:w-1/2 relative group">
                    <div class="absolute inset-0 bg-amber-600/20 blur-[80px] opacity-0 group-hover:opacity-100 transition duration-700 z-0"></div>
                    <img src="{{ asset('test/images/6s.png') }}" alt="The Final Return" class="w-full aspect-[4/3] object-cover rounded-xl shadow-[0_0_40px_rgba(0,0,0,0.8)] relative z-10 border border-zinc-800/80 transition-transform hover:scale-[1.02] duration-700">
                </div>
                <div class="w-full md:w-1/2 space-y-6">
                    <div class="flex items-center gap-6 mb-2 flex-row-reverse">
                        <span class="text-amber-500 font-serif text-6xl opacity-30 font-bold">06</span>
                        <div class="h-px bg-zinc-800 flex-grow"></div>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-serif text-white font-bold md:text-right">{{ __('The Final Return') }}</h3>
                    <p class="text-zinc-400 text-lg md:text-xl leading-relaxed md:text-right">
                        {{ __('Your impeccably restored garments are delivered safely back into your hands via our private, secure fleet.') }}
                    </p>
                </div>
            </div>

        </div>

        <div class="mt-32 text-center scroll-animate">
            <h3 class="text-2xl text-white font-serif mb-8">{{ __('Experience the standard.') }}</h3>
            <a href="{{ url('/order') }}" class="magnetic-btn inline-block luxury-bg text-zinc-950 px-12 py-5 rounded-sm text-lg font-bold tracking-widest uppercase shadow-[0_0_30px_rgba(212,175,55,0.2)]">
                {{ __('Initiate Manifest') }}
            </a>
        </div>
    </div>
</div>
@endsection
