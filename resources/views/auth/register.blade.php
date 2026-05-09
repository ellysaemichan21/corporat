@extends('layouts.storefront')

@section('content')
<div class="py-24 min-h-[80vh] flex items-center justify-center relative overflow-hidden">
    <!-- Subtle Background Element -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber-900/10 rounded-full blur-[150px]"></div>
    </div>

    <div class="max-w-md w-full mx-auto px-6 relative z-10">
        <div class="card-glass rounded-xl p-8 md:p-12 border-zinc-800 shadow-2xl scroll-animate">
            <div class="absolute top-0 inset-x-0 h-1 luxury-bg"></div>
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-serif font-bold text-white mb-2 tracking-tight">{{ __('Request Access') }}</h1>
                <p class="text-zinc-500 text-sm tracking-wide uppercase">{{ __('Establish Your Account') }}</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Full Name') }}</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600">
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-400 text-xs" />
                </div>

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Corporate Email') }}</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-400 text-xs" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Security Password') }}</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-400 text-xs" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-400 text-xs" />
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full luxury-bg text-zinc-950 px-8 py-4 rounded-sm text-sm font-bold transition-transform hover:scale-[1.02] tracking-widest uppercase shadow-[0_0_20px_rgba(212,175,55,0.2)]">
                        {{ __('Establish Account') }}
                    </button>
                </div>

                <div class="text-center mt-6">
                    <p class="text-zinc-600 text-xs uppercase tracking-widest">
                        {{ __('Already a Member?') }} 
                        <a href="{{ route('login') }}" class="text-amber-500 hover:text-amber-400 transition-colors ml-1">{{ __('Access Portal') }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
