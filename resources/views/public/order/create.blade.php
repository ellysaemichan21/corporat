@extends('layouts.storefront')

@section('content')
<div class="py-24 bg-zinc-950 min-h-screen relative overflow-hidden">
    <!-- Subtle background elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-1/4 left-0 w-[500px] h-[500px] bg-amber-900/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="max-w-3xl mx-auto px-6 lg:px-8 relative z-10 scroll-animate">
        <div class="text-center mb-16 scroll-animate delay-100">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">{{ __('Request Service') }}</h1>
            <p class="text-zinc-400 text-lg">{{ __('Enter your details to initiate a pickup. Our concierge will handle the rest.') }}</p>
        </div>

        <div class="card-glass rounded-xl p-8 md:p-12 relative overflow-hidden border-zinc-800 shadow-2xl scroll-animate delay-200">
            <!-- Decorative line -->
            <div class="absolute top-0 inset-x-0 h-1 luxury-bg"></div>

            <form action="{{ route('public.order.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Contact Details Section -->
                <div>
                    <h3 class="text-xl font-serif font-bold text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-px bg-amber-500/50"></span>
                        {{ __('Contact Details') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Full Name') }}</label>
                            <input type="text" id="name" name="name" required class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600" placeholder="e.g. Eleanor Vance">
                            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Phone Number') }}</label>
                            <input type="tel" id="phone" name="phone" required class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600" placeholder="+1 (555) 000-0000">
                            @error('phone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Email Address') }} <span class="text-zinc-600 font-normal normal-case tracking-normal">({{ __('For receipts') }})</span></label>
                            <input type="email" id="email" name="email" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600" placeholder="eleanor@example.com">
                            @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Logistics Section -->
                <div class="pt-6 border-t border-zinc-800">
                    <h3 class="text-xl font-serif font-bold text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-px bg-amber-500/50"></span>
                        {{ __('Logistics') }}
                    </h3>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="address" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Apartment / Unit & Building Address') }}</label>
                            <textarea id="address" name="address" rows="3" required class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600 resize-none" placeholder="Unit 402, The Grand Residences..."></textarea>
                            @error('address') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="tier_preference" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Service Tier Selection') }}</label>
                            <div class="relative">
                                <select id="tier_preference" name="tier_preference" required class="appearance-none w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors font-serif">
                                    <option value="" disabled selected class="bg-zinc-900">{{ __('Select your preferred tier') }}</option>
                                    <option value="Essential" class="bg-zinc-900">{{ __('Essential Care') }}</option>
                                    <option value="Signature" class="bg-zinc-900">{{ __('Signature Care') }}</option>
                                    <option value="Bespoke" class="bg-zinc-900">{{ __('Bespoke Structural Care') }}</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-amber-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('tier_preference') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-8">
                    <button type="submit" class="w-full luxury-bg text-zinc-950 px-8 py-4 rounded-sm text-base font-bold transition-transform hover:scale-[1.02] tracking-wider uppercase shadow-[0_0_20px_rgba(212,175,55,0.2)]">
                        {{ __('Initialize Manifest') }}
                    </button>
                    <p class="text-center text-zinc-600 text-xs mt-4">{{ __('By submitting, you agree to our Terms of Service and Liability Agreement.') }}</p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
