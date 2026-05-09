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
                    <div>
                        <h3 class="text-xl font-serif font-bold text-white mb-4 flex items-center gap-3">
                            <span class="w-8 h-px bg-amber-500/50"></span>
                            {{ __('Order Type') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="is_corporate" value="0" class="peer sr-only" checked onchange="setCorporateMode(false)">
                                <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/30 peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all group-hover:border-zinc-700">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <p class="text-sm font-bold text-white uppercase tracking-wider">{{ __('Personal Order') }}</p>
                                    </div>
                                    <p class="text-xs text-zinc-500">{{ __('Individual or household laundry. All service tiers available.') }}</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="is_corporate" value="1" class="peer sr-only" onchange="setCorporateMode(true)">
                                <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/30 peer-checked:border-blue-500 peer-checked:bg-blue-500/5 transition-all group-hover:border-zinc-700">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <p class="text-sm font-bold text-white uppercase tracking-wider">{{ __('Corporate / Bulk Order') }}</p>
                                    </div>
                                    <p class="text-xs text-zinc-500">{{ __('Hotels, hospitals, gyms & offices. Signature & Bespoke tier only.') }}</p>
                                </div>
                            </label>
                        </div>
                    </div>
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
                        <div class="space-y-2">
                            <div class="flex items-center justify-between mb-1">
                                <label id="date-label" for="expected_datetime" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Preferred Collection Date/Time') }}</label>
                                <div class="flex gap-2">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_priority" id="is_priority" value="1" class="sr-only peer" onchange="toggleAsap(this)">
                                        <div class="px-3 py-1 rounded-full border border-zinc-800 bg-zinc-900/50 text-[10px] font-bold uppercase tracking-widest text-zinc-500 peer-checked:border-amber-500 peer-checked:text-amber-500 peer-checked:bg-amber-500/5 transition-all">
                                            {{ __('ASAP (Priority)') }}
                                        </div>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_fast_track" id="is_fast_track" value="1" class="sr-only peer">
                                        <div class="px-3 py-1 rounded-full border border-zinc-800 bg-zinc-900/50 text-[10px] font-bold uppercase tracking-widest text-zinc-500 peer-checked:border-blue-500 peer-checked:text-blue-500 peer-checked:bg-blue-500/5 transition-all">
                                            {{ __('Fast Track (+30%)') }}
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div id="datetime-wrapper">
                                <input type="datetime-local" id="expected_datetime" name="expected_datetime" required class="w-full bg-zinc-900/50 border border-zinc-800 rounded-sm px-4 py-3 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors" value="{{ old('expected_datetime') }}">
                            </div>
                            @error('expected_datetime') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- ─── Service Tier ────────────────────────────────────── --}}
                        <div class="space-y-2">
                            <label for="tier_preference" class="block text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('Service Tier Selection') }}</label>
                            @error('tier_level') <span class="text-red-400 text-xs block mb-2">{{ $message }}</span> @enderror
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

                {{-- ─── Auto-Weight Bundle Selector ─────────────────────────────── --}}
                <div class="pt-6 border-t border-zinc-800">
                    <h3 class="text-xl font-serif font-bold text-white mb-2 flex items-center gap-3">
                        <span class="w-8 h-px bg-amber-500/50"></span>
                        {{ __('Estimated Order Volume') }}
                    </h3>
                    <p class="text-zinc-500 text-xs mb-6" id="bundle-hint">{{ __('Select a bundle that best matches your laundry load. Final weight is confirmed upon pickup.') }}</p>

                    {{-- Personal Bundles --}}
                    <div id="personal-bundles" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ([
                            ['label' => 'Small Load',  'desc' => '~3 kg · Daily wear', 'kg' => 3,  'icon' => '👕'],
                            ['label' => 'Medium Load', 'desc' => '~7 kg · Weekly wash', 'kg' => 7,  'icon' => '🧺'],
                            ['label' => 'Large Load',  'desc' => '~15 kg · Household', 'kg' => 15, 'icon' => '🏠'],
                        ] as $bundle)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="weight_bundle" value="{{ $bundle['kg'] }}" class="peer sr-only" onchange="applyBundle({{ $bundle['kg'] }}, false)">
                            <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/30 peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all group-hover:border-zinc-700 text-center">
                                <div class="text-2xl mb-2">{{ $bundle['icon'] }}</div>
                                <p class="text-sm font-bold text-white">{{ $bundle['label'] }}</p>
                                <p class="text-xs text-zinc-500 mt-1">{{ $bundle['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Corporate / Bulk Bundles --}}
                    <div id="corporate-bundles" class="hidden grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ([
                            ['label' => 'Standard Bulk',  'desc' => '~50 kg · Small office',       'kg' => 50,  'icon' => '🏢'],
                            ['label' => 'Premium Bulk',   'desc' => '~150 kg · Hotel / Gym',        'kg' => 150, 'icon' => '🏨'],
                            ['label' => 'Enterprise',     'desc' => '~300 kg · Hospital / Resort',  'kg' => 300, 'icon' => '🏥'],
                        ] as $bundle)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="weight_bundle" value="{{ $bundle['kg'] }}" class="peer sr-only" onchange="applyBundle({{ $bundle['kg'] }}, true)">
                            <div class="p-4 rounded-sm border border-zinc-800 bg-zinc-900/30 peer-checked:border-blue-500 peer-checked:bg-blue-500/5 transition-all group-hover:border-zinc-700 text-center">
                                <div class="text-2xl mb-2">{{ $bundle['icon'] }}</div>
                                <p class="text-sm font-bold text-white">{{ $bundle['label'] }}</p>
                                <p class="text-xs text-zinc-500 mt-1">{{ $bundle['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Hidden qty fields populated by JS --}}
                    <div id="hidden-items" class="hidden">
                        @foreach($tiers as $tierName => $services)
                            @foreach($services as $service)
                                <input type="hidden" name="items[{{ $loop->parent->index * 10 + $loop->index }}][service_id]" value="{{ $service->id }}">
                                <input type="hidden" name="items[{{ $loop->parent->index * 10 + $loop->index }}][qty]" value="0" id="qty-{{ $service->id }}">
                            @endforeach
                        @endforeach
                    </div>

                    <p class="text-zinc-600 text-xs mt-4 italic">{{ __('* Kg-based services will have their quantity set automatically from the bundle selection above.') }}</p>
                    @error('items') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
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
@push('scripts')
<script>
    // ─── Service data from PHP ─────────────────────────────────────────────────
    const services = @json($tiers->flatten()->map(fn($s) => ['id' => $s->id, 'unit' => $s->unit_type, 'tier' => $s->tier?->name]));

    // ─── Corporate Mode (for manual toggle by non-partner users) ──────────────
    function setCorporateMode(isCorporate) {
        const partnerSection  = document.getElementById('corporate-partner-section');
        const personalBundles = document.getElementById('personal-bundles');
        const corporateBundles= document.getElementById('corporate-bundles');
        const essentialOption = document.getElementById('tier-essential');
        const tierSelect      = document.getElementById('tier_preference');
        const hintEl          = document.getElementById('bundle-hint');
        const partnerSelect   = document.getElementById('partner_id');

        // Null-safe: elements may not exist for partner users
        if (partnerSection)   partnerSection.classList.toggle('hidden', !isCorporate);
        if (personalBundles)  personalBundles.classList.toggle('hidden', isCorporate);
        if (corporateBundles) corporateBundles.classList.toggle('hidden', !isCorporate);

        if (essentialOption) essentialOption.disabled = isCorporate;
        if (isCorporate && tierSelect && tierSelect.value === 'Essential') tierSelect.value = '';
        if (partnerSelect && partnerSelect.tagName === 'SELECT') partnerSelect.required = isCorporate;

        if (hintEl) hintEl.textContent = isCorporate
            ? 'Select a bulk volume bundle suitable for your facility. Final weight confirmed at pickup.'
            : 'Select a bundle that best matches your laundry load. Final weight is confirmed upon pickup.';

        document.querySelectorAll('[id^="qty-"]').forEach(el => el.value = 0);
    }

    // ─── Partner init: directly force corporate bundle view ────────────────────
    function initPartnerMode() {
        const personalBundles  = document.getElementById('personal-bundles');
        const corporateBundles = document.getElementById('corporate-bundles');
        const essentialOption  = document.getElementById('tier-essential');
        const tierSelect       = document.getElementById('tier_preference');
        const hintEl           = document.getElementById('bundle-hint');

        // Hide personal (small/medium/large), show corporate (50/150/300kg)
        if (personalBundles)  personalBundles.classList.add('hidden');
        if (corporateBundles) corporateBundles.classList.remove('hidden');

        // Lock out Essential tier
        if (essentialOption) {
            essentialOption.disabled = true;
            essentialOption.style.display = 'none';
        }
        if (tierSelect && tierSelect.value === 'Essential') tierSelect.value = '';

        if (hintEl) hintEl.textContent = 'Select a bulk volume bundle suitable for your facility. Final weight confirmed at pickup.';

        document.querySelectorAll('[id^="qty-"]').forEach(el => el.value = 0);
    }

    // ─── Apply bundle weight to kg-based services ──────────────────────────────
    function applyBundle(kg, isCorporate) {
        services.forEach(service => {
            const input = document.getElementById('qty-' + service.id);
            if (!input) return;
            // Only set qty for kg-based services
            if (service.unit === 'kg') {
                input.value = kg;
            } else {
                // For pcs-based corporate tier, estimate qty as kg / 1.5 (avg garment weight)
                input.value = isCorporate ? Math.round(kg / 1.5) : 1;
            }
        });
    }

    // ─── Delivery toggle ───────────────────────────────────────────────────────
    function toggleDelivery(method) {
        const collectionFields = document.getElementById('collection-fields');
        const dateLabel = document.getElementById('date-label');

        if (method === 'collection') {
            collectionFields.style.display = 'block';
            dateLabel.innerText = "{{ __('Preferred Collection Date/Time') }}";
            document.getElementById('pickup_address').setAttribute('required', 'required');
        } else {
            collectionFields.style.display = 'none';
            dateLabel.innerText = "{{ __('Expected Drop-off Date') }}";
            document.getElementById('pickup_address').removeAttribute('required');
        }
    }

    // ─── ASAP toggle ──────────────────────────────────────────────────────────
    function toggleAsap(checkbox) {
        const datetimeInput = document.getElementById('expected_datetime');
        const wrapper = document.getElementById('datetime-wrapper');

        if (checkbox.checked) {
            datetimeInput.removeAttribute('required');
            datetimeInput.value = '';
            wrapper.style.opacity = '0.3';
            wrapper.style.pointerEvents = 'none';
        } else {
            datetimeInput.setAttribute('required', 'required');
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
        }
    }

    // ─── Init ─────────────────────────────────────────────────────────────────
    const IS_PARTNER = {{ $isPartnerUser ? 'true' : 'false' }};

    window.addEventListener('load', () => {
        document.getElementById('hidden-items').classList.remove('hidden');

        if (IS_PARTNER) {
            // Partner: directly force corporate bundle view (no toggle elements)
            initPartnerMode();
        } else {
            const checkedDelivery = document.querySelector('input[name="delivery_method"]:checked');
            if (checkedDelivery) toggleDelivery(checkedDelivery.value);
        }
    });
</script>
@endpush
@endsection
