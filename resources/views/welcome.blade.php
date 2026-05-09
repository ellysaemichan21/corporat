@extends('layouts.storefront')

@section('content')
<!-- Hero Section -->
<div id="parallax-hero" class="relative overflow-hidden pt-40 pb-20 lg:pt-64 lg:pb-32">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/60 to-zinc-950/10 z-10"></div>
        <img id="parallax-bg" src="{{ asset('test/images/1.png') }}" alt="Facade" class="w-full h-full object-cover object-top opacity-60 mix-blend-overlay scale-105 transition-transform duration-700 ease-out">
    </div>

    <div id="parallax-content" class="relative z-20 max-w-7xl mx-auto px-6 lg:px-8 text-center scroll-animate transition-transform duration-700 ease-out">
        <h1 class="text-5xl md:text-7xl font-serif font-bold tracking-tight text-white mb-6 leading-tight drop-shadow-lg scroll-animate delay-100">
            {{ __('Redefining Laundry for the Elite') }}
        </h1>
        
        <h2 class="text-2xl md:text-3xl font-serif italic text-amber-500 mb-8 drop-shadow-md scroll-animate delay-200">
            {{ __('Bespoke Garment Care. Unmatched B2B Logistics.') }}
        </h2>
        
        <p class="mt-6 text-lg md:text-xl leading-relaxed text-zinc-300 max-w-3xl mx-auto mb-10 bg-zinc-950/40 p-6 rounded-lg backdrop-blur-sm border border-zinc-800/50 scroll-animate delay-300">
            {{ __('The Flagship Facade: Our premier location—where artisanal care meets high-capacity logistics.') }}
        </p>
        
        <div class="flex justify-center scroll-animate delay-400">
            <a href="{{ url('/order') }}" class="magnetic-btn inline-block luxury-bg text-zinc-950 px-10 py-5 rounded-sm text-lg font-bold tracking-wider uppercase shadow-[0_0_30px_rgba(212,175,55,0.3)]">
                {{ __('Secure Your Collection') }}
            </a>
        </div>
    </div>
</div>

<!-- Section 2: Inside the Artisan Workshop -->
<div class="py-24 border-t border-zinc-900 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 scroll-animate relative z-20">
        <div class="flex flex-col md:flex-row items-center gap-16">
            <div class="w-full md:w-1/2 relative group flex justify-center">
                <div class="absolute inset-0 bg-amber-500/20 blur-[80px] z-0 opacity-50 group-hover:opacity-80 transition-opacity duration-700"></div>
                <img src="{{ asset('test/images/2.png') }}" alt="Inside the Artisan Workshop" class="relative z-10 w-full max-w-md h-auto rounded-xl shadow-2xl border border-zinc-800/80 transform transition-transform duration-700 group-hover:scale-[1.02]">
            </div>
            <div class="w-full md:w-1/2 space-y-6">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-white">{{ __('Craftsmanship in Every Fiber') }}</h2>
                <div class="w-16 h-1 luxury-bg"></div>
                <p class="text-zinc-400 text-lg leading-relaxed">
                    {{ __('Dedicated Artisans: Our certified technicians provide specialized care for every fiber. Every garment that enters our facility is subjected to a meticulous inspection process before it even touches water or solvent.') }}
                </p>
                <p class="text-zinc-400 text-lg leading-relaxed">
                    {{ __('We blend traditional, time-honored hand-finishing techniques with cutting-edge eco-friendly chemistry. This ensures that delicate silks, vintage couture, and heavy wools retain their architectural integrity and original luster.') }}
                </p>
                <p class="text-zinc-400 text-lg leading-relaxed">
                    {{ __('It is not just laundry; it is a restoration process led by masters of the craft who understand the exact pedigree of your wardrobe.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Section 3: VIP Concierge Logistics -->
<div class="py-24 border-t border-zinc-800 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 scroll-animate relative z-20">
        <div class="flex flex-col md:flex-row-reverse items-center gap-16">
            <div class="w-full md:w-1/2 relative group flex justify-center">
                <div class="absolute inset-0 bg-blue-500/10 blur-[80px] z-0 opacity-50 group-hover:opacity-80 transition-opacity duration-700"></div>
                <img src="{{ asset('test/images/3.png') }}" alt="VIP Concierge Logistics" class="relative z-10 w-full max-w-md h-auto rounded-xl shadow-2xl border border-zinc-800/80 transform transition-transform duration-700 group-hover:scale-[1.02]">
            </div>
            <div class="w-full md:w-1/2 space-y-6">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-white">{{ __('White-Glove Fleet Service') }}</h2>
                <div class="w-16 h-1 luxury-bg"></div>
                <p class="text-zinc-400 text-lg leading-relaxed">
                    {{ __('Elite Concierge Delivery: Our drivers utilize private, climate-controlled vehicles for maximum discretion. From the moment your garments leave your executive suite, they are treated with the utmost respect and security.') }}
                </p>
                <p class="text-zinc-400 text-lg leading-relaxed">
                    {{ __('We guarantee an uninterrupted chain of custody. Our logistics team operates with absolute punctuality, ensuring that your perfectly pressed garments are returned exactly when and where you need them.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Section 4: The Executive Guarantee -->
<div class="py-24 border-t border-zinc-900 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center scroll-animate relative z-20">
        <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">{{ __('Unwavering Accountability') }}</h2>
        <div class="w-16 h-1 luxury-bg mx-auto mb-10"></div>
        <p class="text-zinc-400 text-xl leading-relaxed max-w-4xl mx-auto mb-16">
            {{ __('Executive Welcome Center: The highest authority at Bespoke Laundry—personifying accountability beneath our seal.') }}
        </p>
        
        <div class="relative group max-w-3xl mx-auto mt-8 flex justify-center">
            <div class="absolute inset-0 bg-amber-600/20 blur-[100px] z-0 opacity-40 group-hover:opacity-70 transition-opacity duration-700"></div>
            <img src="{{ asset('test/images/4.png') }}" alt="The Executive Guarantee" class="relative z-10 w-full max-h-[650px] object-contain rounded-2xl shadow-[0_0_40px_rgba(212,175,55,0.1)] border border-amber-500/20 transform transition-transform duration-700 group-hover:scale-[1.02]">
        </div>

        <div class="mt-16 max-w-2xl mx-auto text-center">
            <h3 class="text-2xl font-serif italic text-amber-500 mb-4">{{ __('Thank you for trusting Felix.') }}</h3>
            <p class="text-zinc-400 text-lg leading-relaxed">
                {{ __('We consider it a privilege to serve you and are deeply committed to maintaining the highest standard of excellence in the industry. Your satisfaction is our absolute priority.') }}
            </p>
        </div>
    </div>
</div>
@endsection
