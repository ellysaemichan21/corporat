@extends('layouts.storefront')

@section('content')
<div class="py-24 min-h-screen relative overflow-hidden">
    <!-- Subtle background elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-1/4 left-0 w-[500px] h-[500px] bg-amber-900/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="max-w-3xl mx-auto px-6 lg:px-8 relative z-20 scroll-animate">
        <div class="text-center mb-16 scroll-animate delay-100">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">{{ __('Request Service') }}</h1>
            <p class="text-zinc-400 text-lg">{{ __('Enter your details to initiate a pickup. Our concierge will handle the rest.') }}</p>
        </div>

        <div class="card-glass rounded-xl p-8 md:p-12 relative overflow-hidden border-zinc-800 shadow-2xl scroll-animate delay-200">
            <!-- Decorative line -->
            <div class="absolute top-0 inset-x-0 h-1 luxury-bg"></div>

            <form action="{{ route('public.order.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- ─── ORDER TYPE ──────────────────────────────────────────────── --}}
                @php $isPartnerUser = auth()->check() && auth()->user()->isPartner(); @endphp

                @if($isPartnerUser)
                    {{-- Partner: locked to corporate, no toggle shown --}}
                    <input type="hidden" name="is_corporate" value="1">
                    <div class="p-4 rounded-sm border border-blue-500/40 bg-blue-500/5 flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <div>
                            <p class="text-blue-300 font-bold text-sm uppercase tracking-wider">Corporate / Bulk Order</p>
                            <p class="text-zinc-400 text-xs mt-0.5">Auto-detected from your partner account. Signature & Bespoke tiers available.</p>
                        </div>
                        <div class="ml-auto flex items-center gap-1.5 px-2 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30">
                            <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></div>
                            <span class="text-emerald-400 text-[10px] font-bold uppercase tracking-wider">15% Discount Applied</span>
                        </div>
                    </div>
                @else
                    {{-- Personal account: always personal, no corporate option --}}
                    <input type="hidden" name="is_corporate" value="0">
                @endif

                {{-- ─── CORPORATE: Partner Selection ────────────────────────────── --}}
                @if($isPartnerUser)
                    {{-- Auto-filled, locked to their company --}}
                    <input type="hidden" name="partner_id" value="{{ auth()->user()->partner_id }}">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Partner Company / Facility') }}</label>
                        <div class="w-full bg-zinc-900/30 border border-blue-500/30 rounded-sm px-4 py-3 flex items-center gap-3">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-white font-medium">{{ auth()->user()->partner->name }}</span>
                            <span class="ml-auto text-[10px] text-blue-400 uppercase tracking-widest">Auto-detected</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-sm border border-blue-500/20 bg-blue-500/5">
                        <p class="text-zinc-400 text-xs">⚠ Essential (Tier 1) is not available for bulk orders. Please select <strong class="text-white">Signature</strong> or <strong class="text-white">Bespoke</strong> tier.</p>
                    </div>
                @else
                    <div id="corporate-partner-section" class="hidden pt-2">
                        <div class="p-4 rounded-sm border border-blue-500/30 bg-blue-500/5 mb-4">
                            <p class="text-blue-400 text-xs font-bold uppercase tracking-widest mb-1">⚠ Corporate Order Active</p>
                            <p class="text-zinc-400 text-xs">Essential (Tier 1) is not available for bulk orders. Please select Signature or Bespoke tier below.</p>
                        </div>
                        <div class="space-y-2">
                            <label for="partner_id" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Partner Company / Facility') }}</label>
                            <div class="relative">
                                <select id="partner_id" name="partner_id" class="appearance-none w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors font-serif">
                                    <option value="" disabled selected class="bg-zinc-900">{{ __('Select your company / facility') }}</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}" class="bg-zinc-900">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-blue-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('partner_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                {{-- ─── Contact Details ─────────────────────────────────────────── --}}
                <div class="pt-6 border-t border-zinc-800">
                    <h3 class="text-xl font-serif font-bold text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-px bg-amber-500/50"></span>
                        {{ __('Contact Details') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Full Name / PIC') }}</label>
                            <input type="text" id="name" name="name" required class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600" placeholder="e.g. Eleanor Vance" value="{{ old('name', $userCustomer?->name ?? auth()->user()?->name ?? '') }}">
                            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Phone Number') }}</label>
                            <input type="tel" id="phone" name="phone" required class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600" placeholder="+62 812-xxxx-xxxx" value="{{ old('phone', $userCustomer?->phone ?? '') }}">
                            @error('phone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Email Address') }} <span class="text-zinc-600 font-normal normal-case tracking-normal">({{ __('For receipts') }})</span></label>
                            <input type="email" id="email" name="email" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600" placeholder="eleanor@example.com" value="{{ old('email', auth()->user()?->email ?? '') }}">
                            @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- ─── Logistics & Delivery ────────────────────────────────────── --}}
                <div class="pt-6 border-t border-zinc-800">
                    <h3 class="text-xl font-serif font-bold text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-px bg-amber-500/50"></span>
                        {{ __('Logistics & Delivery') }}
                    </h3>

                    <!-- Delivery Method Toggle -->
                    @if($isPartnerUser)
                        {{-- Corporate: Concierge Collection only, no drop-off --}}
                        <input type="hidden" name="delivery_method" value="collection">
                        <div class="p-4 rounded-sm border border-amber-500/30 bg-amber-500/5 mb-8 flex items-center gap-3">
                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <div>
                                <p class="text-amber-300 font-bold text-sm uppercase tracking-wider">Concierge Collection</p>
                                <p class="text-zinc-400 text-xs mt-0.5">Our fleet will collect from your facility. Drop-off is not available for corporate bulk orders.</p>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="delivery_method" value="collection" class="peer sr-only" checked onchange="toggleDelivery('collection')">
                                <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/30 peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all group-hover:border-zinc-700">
                                    <p class="text-sm font-bold text-white uppercase tracking-wider mb-1">{{ __('Concierge Collection') }}</p>
                                    <p class="text-xs text-zinc-500">{{ __('Our fleet collects from your location.') }}</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="delivery_method" value="dropoff" class="peer sr-only" onchange="toggleDelivery('dropoff')">
                                <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/30 peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all group-hover:border-zinc-700">
                                    <p class="text-sm font-bold text-white uppercase tracking-wider mb-1">{{ __('Flagship Drop-off') }}</p>
                                    <p class="text-xs text-zinc-500">{{ __('Deliver directly to our intake facility.') }}</p>
                                </div>
                            </label>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Collection Fields -->
                        <div id="collection-fields" class="space-y-6">
                            <div class="space-y-2">
                                <label for="pickup_address" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Pickup Address') }}</label>
                                <textarea id="pickup_address" name="pickup_address" rows="3" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors placeholder-zinc-600 resize-none" placeholder="e.g. Unit 402, The Grand Residences...">{{ old('pickup_address', $userCustomer?->address ?? '') }}</textarea>
                                @error('pickup_address') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Date/Time -->
                        <div class="space-y-2" id="datetime-section">
                            <div class="flex items-center justify-between mb-1">
                                <label id="date-label" for="expected_datetime" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Preferred Collection Date/Time') }}</label>
                                <div class="flex gap-2" id="asap-wrapper">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_priority" id="is_priority" value="1" class="sr-only peer" onchange="toggleAsap(this)">
                                        <div class="px-3 py-1 rounded-full border border-zinc-800 bg-zinc-900/50 text-[10px] font-bold uppercase tracking-widest text-zinc-500 peer-checked:border-amber-500 peer-checked:text-amber-500 peer-checked:bg-amber-500/5 transition-all">
                                            {{ __('ASAP (Priority)') }}
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div id="datetime-wrapper" onclick="alert('Maaf Pak Untuk Uji Coba Silahkan Pakai ASAP');" class="cursor-pointer">
                                <input type="text" id="expected_datetime" name="expected_datetime" readonly class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-zinc-500 focus:outline-none transition-colors cursor-not-allowed pointer-events-none" placeholder="Silahkan centang ASAP / Priority" value="{{ old('expected_datetime') }}">
                            </div>
                            @error('expected_datetime') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- ─── Service Tier ────────────────────────────────────── --}}
                        <div class="space-y-2">
                            <label for="tier_preference" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Service Tier Selection') }}</label>
                            @error('tier_preference') <span class="text-red-400 text-xs block mb-2">{{ $message }}</span> @enderror
                            <div class="relative">
                                <select id="tier_preference" name="tier_preference" required class="appearance-none w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors font-serif">
                                    <option value="" disabled selected class="bg-zinc-900">{{ __('Select your preferred tier') }}</option>
                                    <option value="Essential" id="tier-essential" class="bg-zinc-900">{{ __('Essential Care') }}</option>
                                    <option value="Signature" class="bg-zinc-900">{{ __('Signature Care') }}</option>
                                    <option value="Bespoke" class="bg-zinc-900">{{ __('Bespoke Structural Care') }}</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-amber-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── Auto-Weight Bundle Selector (The Core) ─────────────────── --}}
                <div class="pt-6 border-t border-zinc-800">
                    <h3 class="text-xl font-serif font-bold text-white mb-2 flex items-center gap-3">
                        <span class="w-8 h-px bg-amber-500/50"></span>
                        {{ __('Order Volume') }}
                    </h3>
                    <p class="text-zinc-500 text-xs mb-6" id="bundle-hint">{{ __('Select the package that best matches your laundry load.') }}</p>

                    {{-- Personal Bundles --}}
                    <div id="personal-bundles" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                        @foreach ([
                            ['label' => 'Small Load',  'desc' => '~3 kg', 'kg' => 3,  'icon' => '👕'],
                            ['label' => 'Medium Load', 'desc' => '~7 kg', 'kg' => 7,  'icon' => '🧺'],
                            ['label' => 'Large Load',  'desc' => '~15 kg', 'kg' => 15, 'icon' => '🏠'],
                        ] as $bundle)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="weight_bundle" value="{{ $bundle['kg'] }}" class="peer sr-only" onchange="updateBundleSelection({{ $bundle['kg'] }})">
                            <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/30 peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all group-hover:border-zinc-700 text-center">
                                <div class="text-2xl mb-2">{{ $bundle['icon'] }}</div>
                                <p class="text-sm font-bold text-white">{{ $bundle['label'] }}</p>
                                <p class="text-[10px] text-zinc-500 mt-1 uppercase tracking-widest">{{ $bundle['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Corporate Bundles --}}
                    <div id="corporate-bundles" class="hidden grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                        @foreach ([
                            ['label' => 'Bulk 50',  'desc' => '50 kg',  'kg' => 50,  'icon' => '🏢'],
                            ['label' => 'Bulk 150', 'desc' => '150 kg', 'kg' => 150, 'icon' => '🏨'],
                            ['label' => 'Bulk 300', 'desc' => '300 kg', 'kg' => 300, 'icon' => '🏥'],
                        ] as $bundle)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="weight_bundle" value="{{ $bundle['kg'] }}" class="peer sr-only" onchange="updateBundleSelection({{ $bundle['kg'] }})">
                            <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/30 peer-checked:border-blue-500 peer-checked:bg-blue-500/5 transition-all group-hover:border-zinc-700 text-center">
                                <div class="text-2xl mb-2">{{ $bundle['icon'] }}</div>
                                <p class="text-sm font-bold text-white">{{ $bundle['label'] }}</p>
                                <p class="text-[10px] text-zinc-500 mt-1 uppercase tracking-widest">{{ $bundle['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ─── Services & Treatments (Selection) ──────────────────────── --}}
                <div class="pt-6 border-t border-zinc-800">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-serif font-bold text-white flex items-center gap-3">
                            <span class="w-8 h-px bg-amber-500/50"></span>
                            {{ __('Included Services') }}
                        </h3>
                        <div>
                            <button type="button" id="btn-do-the-best" onclick="doTheBest()" 
                                    class="px-4 py-2 rounded-full border border-amber-500/30 bg-amber-500/10 text-amber-400 text-[10px] font-bold uppercase tracking-widest hover:bg-amber-500/20 hover:border-amber-500/50 transition-all flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                {{ __('Do the best') }}
                            </button>
                            <button type="button" id="btn-choose-myself" onclick="chooseMyself()" 
                                    class="hidden px-4 py-2 rounded-full border border-zinc-700 bg-zinc-800/50 text-zinc-400 text-[10px] font-bold uppercase tracking-widest hover:bg-zinc-800 hover:text-white transition-all flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                {{ __('Choose myself') }}
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="auto_services" id="auto_services_input" value="0">
                    
                    {{-- Auto-select message (hidden by default) --}}
                    <div id="auto-services-msg" class="hidden">
                        <div class="p-6 rounded-sm border border-amber-500/20 bg-amber-500/5 text-center">
                            <div class="text-3xl mb-3">✨</div>
                            <p class="text-sm font-bold text-amber-400 uppercase tracking-widest mb-2">{{ __('Our experts will handle it') }}</p>
                            <p class="text-xs text-zinc-400">{{ __('Our team will inspect your garments and select the best services for optimal care. You will see the full breakdown on your tracking page.') }}</p>
                        </div>
                    </div>

                    {{-- Manual service selection (shown by default) --}}
                    <div id="services-grid">
                        @foreach($tiers as $tierName => $services)
                            <div class="tier-service-group hidden" data-tier="{{ $tierName }}">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($services as $service)
                                        <label class="relative cursor-pointer group">
                                            <input type="checkbox" 
                                                   name="items[{{ $service->id }}][selected]" 
                                                   value="1" 
                                                   class="peer sr-only service-checkbox"
                                                   data-service-id="{{ $service->id }}"
                                                   data-unit="{{ $service->unit_type }}"
                                                   data-price="{{ $service->price }}"
                                                   onchange="syncServiceValues();">
                                            
                                            <input type="hidden" name="items[{{ $service->id }}][service_id]" value="{{ $service->id }}">
                                            <input type="hidden" name="items[{{ $service->id }}][qty]" id="qty-input-{{ $service->id }}" value="0">

                                            <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/20 peer-checked:border-amber-500/50 peer-checked:bg-amber-500/5 transition-all group-hover:border-zinc-700">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="text-xs font-bold text-white uppercase tracking-tight group-hover:text-amber-500 transition-colors">{{ $service->name }}</p>
                                                        <p class="text-[9px] text-zinc-500 uppercase tracking-widest mt-1">Rp {{ number_format($service->price, 0, ',', '.') }} / {{ $service->unit_type }}</p>
                                                    </div>
                                                    <div class="w-4 h-4 rounded-full border border-zinc-800 flex items-center justify-center peer-checked:bg-amber-500 transition-all">
                                                        <div class="w-1.5 h-1.5 bg-white rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                </div>




                {{-- ─── Promo Code (Personal Only) ─────────────────────────────── --}}
                <div id="promo-section" class="pt-6 border-t border-zinc-800 {{ $isPartnerUser ? 'hidden' : '' }}">
                    <h3 class="text-xl font-serif font-bold text-white mb-4 flex items-center gap-3">
                        <span class="w-8 h-px bg-amber-500/50"></span>
                        {{ __('Promo Code') }}
                    </h3>
                    <div class="flex gap-3">
                        <div class="flex-1 relative">
                            <input type="text" id="promo_code_input" name="promo_code" 
                                   class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white uppercase tracking-widest focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors placeholder-zinc-600 text-sm font-mono" 
                                   placeholder="{{ __('e.g. INSTAGRAM10') }}"
                                   value="{{ old('promo_code') }}">
                        </div>
                        <button type="button" onclick="validatePromo()" 
                                class="px-6 py-3 rounded-sm border border-emerald-500/30 bg-emerald-500/10 text-emerald-400 text-xs font-bold uppercase tracking-widest hover:bg-emerald-500/20 transition-all">
                            {{ __('Apply') }}
                        </button>
                    </div>
                    <div id="promo-feedback" class="mt-3 hidden">
                        <div id="promo-success" class="hidden p-3 rounded-sm border border-emerald-500/30 bg-emerald-500/5 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span id="promo-success-text" class="text-emerald-400 text-xs font-bold uppercase tracking-widest"></span>
                        </div>
                        <div id="promo-error" class="hidden p-3 rounded-sm border border-red-500/30 bg-red-500/5 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span class="text-red-400 text-xs font-bold uppercase tracking-widest">{{ __('Invalid or expired promo code') }}</span>
                        </div>
                    </div>
                    @error('promo_code') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mt-12 flex flex-col gap-6 scroll-animate delay-300">
                    <button type="submit" class="w-full luxury-bg text-zinc-950 py-5 rounded-sm text-sm font-bold uppercase tracking-[0.3em] shadow-[0_0_30px_rgba(212,175,55,0.2)] hover:scale-[1.02] active:scale-[0.98] transition-all">
                        {{ __('Initialize Manifest') }}
                    </button>
                    <p class="text-center text-[10px] text-zinc-600 uppercase tracking-[0.4em]">{{ __('Hand-crafted care for every garment') }}</p>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    // ─── Order Logic ──────────────────────────────────────────────────────────
    let currentSelectedKg = 0;

    function updateBundleSelection(targetKg) {
        let randomized;
        
        // Artisanal Weight Randomization Ranges
        if (targetKg <= 3) {
            randomized = 2.3 + (Math.random() * 1.2); // 2.3 - 3.5 kg
        } else if (targetKg <= 7) {
            randomized = 6.0 + (Math.random() * 2.5); // 6.0 - 8.5 kg
        } else if (targetKg <= 15) {
            randomized = 11.5 + (Math.random() * 6.5); // 11.5 - 18.0 kg
        } else if (targetKg <= 50) {
            randomized = 42.0 + (Math.random() * 18.0); // 42.0 - 60.0 kg
        } else if (targetKg <= 150) {
            randomized = 125.0 + (Math.random() * 45.0); // 125.0 - 170.0 kg
        } else {
            randomized = 185.0 + (Math.random() * 115.0); // 185.0 - 300.0 kg (Above 150)
        }

        currentSelectedKg = Math.round(randomized * 10) / 10;
        syncServiceValues();
        updateEstimate();
    }

    function syncServiceValues() {
        const selectedCheckboxes = Array.from(document.querySelectorAll('.service-checkbox:checked'));
        const kgServices = selectedCheckboxes.filter(cb => cb.dataset.unit === 'kg');
        const pcsServices = selectedCheckboxes.filter(cb => cb.dataset.unit !== 'kg');
        
        // 1. Reset all first
        document.querySelectorAll('input[id^="qty-input-"]').forEach(i => i.value = 0);

        // 2. Handle Piece-based items - Each gets a sensible whole number qty
        if (pcsServices.length > 0 && currentSelectedKg > 0) {
            // Distribute the bundle as whole-number pieces across pcs services
            const totalPieces = Math.max(1, Math.round(currentSelectedKg));
            if (pcsServices.length === 1) {
                document.getElementById('qty-input-' + pcsServices[0].dataset.serviceId).value = totalPieces;
            } else {
                let remaining = totalPieces;
                pcsServices.forEach((cb, index) => {
                    const qtyInput = document.getElementById('qty-input-' + cb.dataset.serviceId);
                    if (index === pcsServices.length - 1) {
                        qtyInput.value = Math.max(1, remaining);
                    } else {
                        const share = Math.max(1, Math.round(remaining / (pcsServices.length - index)));
                        qtyInput.value = share;
                        remaining -= share;
                    }
                });
            }
        } else {
            pcsServices.forEach(cb => {
                document.getElementById('qty-input-' + cb.dataset.serviceId).value = 1;
            });
        }

        // 3. Handle Kg-based items (Essential/Bulk) - Share the bundle weight
        if (kgServices.length > 0 && currentSelectedKg > 0) {
            if (kgServices.length === 1) {
                // Just one service? Give it the full volume
                document.getElementById('qty-input-' + kgServices[0].dataset.serviceId).value = currentSelectedKg;
            } else {
                // Multiple services? Distribute randomly but sum to total bundle volume
                let remainingKg = currentSelectedKg;
                kgServices.forEach((cb, index) => {
                    const qtyInput = document.getElementById('qty-input-' + cb.dataset.serviceId);
                    
                    if (index === kgServices.length - 1) {
                        // Last item gets the remainder
                        qtyInput.value = Math.round(remainingKg * 10) / 10;
                    } else {
                        // Pick a random portion (between 30% and 70% of remaining)
                        const portion = remainingKg * (0.3 + Math.random() * 0.4);
                        const value = Math.round(portion * 10) / 10;
                        qtyInput.value = value;
                        remainingKg -= value;
                    }
                });
            }
        }
    }

    function updateServiceTierVisibility() {
        const tierSelect = document.getElementById('tier_preference');
        const selectedTier = tierSelect.value;
        
        document.querySelectorAll('.tier-service-group').forEach(el => {
            if (el.dataset.tier === selectedTier) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
                // Uncheck services in hidden tiers
                el.querySelectorAll('.service-checkbox').forEach(cb => cb.checked = false);
            }
        });
        syncServiceValues();
    }

    // ─── Delivery & Corporate Toggles ──────────────────────────────────────────
    function toggleDelivery(method) {
        const collectionFields = document.getElementById('collection-fields');
        const datetimeSection = document.getElementById('datetime-section');
        const dateLabel = document.getElementById('date-label');
        const datetimeInput = document.getElementById('expected_datetime');

        if (method === 'collection') {
            collectionFields.style.display = 'block';
            if (datetimeSection) datetimeSection.style.display = 'block';
            dateLabel.innerText = "{{ __('Preferred Collection Date/Time') }}";
        } else {
            collectionFields.style.display = 'none';
            if (datetimeSection) datetimeSection.style.display = 'none';
            // Clear datetime value so backend doesn't reject it
            if (datetimeInput) datetimeInput.value = '';
            dateLabel.innerText = "{{ __('Expected Drop-off Date') }}";
        }
    }

    function setCorporateMode(isCorporate) {
        const partnerSection = document.getElementById('corporate-partner-section');
        const personal = document.getElementById('personal-bundles');
        const corporate = document.getElementById('corporate-bundles');
        const essentialOption = document.getElementById('tier-essential');
        const tierSelect = document.getElementById('tier_preference');
        const promoSection = document.getElementById('promo-section');

        if (partnerSection) partnerSection.classList.toggle('hidden', !isCorporate);
        if (personal) personal.classList.toggle('hidden', isCorporate);
        if (corporate) corporate.classList.toggle('hidden', !isCorporate);
        if (promoSection) promoSection.classList.toggle('hidden', isCorporate);

        // Clear promo if switching to corporate
        if (isCorporate) {
            const promoInput = document.getElementById('promo_code_input');
            if (promoInput) promoInput.value = '';
        }

        if (essentialOption) {
            essentialOption.disabled = isCorporate;
            if (isCorporate && tierSelect.value === 'Essential') {
                tierSelect.value = '';
                updateServiceTierVisibility();
            }
        }
    }

    // ─── "Do the best" — Let the experts handle it ──────────────────────────
    function doTheBest() {
        // Hide service checkboxes, show auto message
        document.getElementById('services-grid').classList.add('hidden');
        document.getElementById('auto-services-msg').classList.remove('hidden');
        document.getElementById('auto_services_input').value = '1';

        // Swap buttons
        document.getElementById('btn-do-the-best').classList.add('hidden');
        document.getElementById('btn-choose-myself').classList.remove('hidden');

        // Uncheck all services and reset qtys
        document.querySelectorAll('.service-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('input[id^="qty-input-"]').forEach(i => i.value = 0);
    }

    function chooseMyself() {
        // Show service checkboxes, hide auto message
        document.getElementById('services-grid').classList.remove('hidden');
        document.getElementById('auto-services-msg').classList.add('hidden');
        document.getElementById('auto_services_input').value = '0';

        // Swap buttons
        document.getElementById('btn-do-the-best').classList.remove('hidden');
        document.getElementById('btn-choose-myself').classList.add('hidden');

        // Re-show the correct tier
        updateServiceTierVisibility();
    }

    // ─── Promo Code Validation ────────────────────────────────────────────────
    const validPromos = @json($activePromos->pluck('code')->map(fn($c) => strtoupper($c)));
    const promoDetails = @json($activePromos);

    function validatePromo() {
        const input = document.getElementById('promo_code_input');
        const code = input.value.trim().toUpperCase();
        const feedback = document.getElementById('promo-feedback');
        const success = document.getElementById('promo-success');
        const error = document.getElementById('promo-error');
        const successText = document.getElementById('promo-success-text');

        feedback.classList.remove('hidden');

        if (validPromos.includes(code)) {
            const promo = promoDetails.find(p => p.code.toUpperCase() === code);
            const desc = promo ? `${promo.description} (${promo.value}% off)` : 'Promo applied!';
            successText.textContent = desc;
            success.classList.remove('hidden');
            error.classList.add('hidden');
            input.value = code;
        } else {
            success.classList.add('hidden');
            error.classList.remove('hidden');
            input.value = '';
        }
    }

    // ─── Init ─────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        updateServiceTierVisibility();
        document.getElementById('tier_preference').addEventListener('change', updateServiceTierVisibility);

        const checkedDelivery = document.querySelector('input[name="delivery_method"]:checked');
        if (checkedDelivery) toggleDelivery(checkedDelivery.value);

        // If partner/corporate, disable Essential tier, show corporate bundles, hide promo, auto "do the best"
        @if($isPartnerUser)
            const essentialOption = document.getElementById('tier-essential');
            if (essentialOption) essentialOption.disabled = true;
            document.getElementById('personal-bundles').classList.add('hidden');
            document.getElementById('corporate-bundles').classList.remove('hidden');
            
            // Hide promo section — partners already have partner discount
            const promoSection = document.getElementById('promo-section');
            if (promoSection) promoSection.classList.add('hidden');

            // Auto-activate "Do the best" — bulk orders let the system decide
            doTheBest();
            // Hide the toggle buttons too — partners can't switch back
            document.getElementById('btn-choose-myself').classList.add('hidden');
        @endif
    });
</script>
@endpush
@endsection

