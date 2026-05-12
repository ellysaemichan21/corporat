@extends('layouts.storefront')

@section('content')
<div class="py-24 min-h-screen relative overflow-hidden">
    <!-- Subtle Background Element -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-0 w-[600px] h-[600px] bg-amber-900/5 rounded-full blur-[150px]"></div>
    </div>

    <div class="max-w-4xl mx-auto px-6 lg:px-8 relative z-10">
        <div class="mb-12 scroll-animate">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-zinc-500 hover:text-amber-500 transition-colors text-xs font-bold uppercase tracking-widest mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Back to Portal') }}
            </a>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-3xl font-serif font-bold text-white mb-1">{{ __('Collection Tracking') }}</h1>
                    <p class="text-amber-500 font-mono text-sm tracking-wider">{{ $transaction->invoice_code }}</p>
                </div>
                <div class="text-right">
                    <p class="text-zinc-500 text-[10px] uppercase tracking-widest mb-1">{{ __('Current Status') }}</p>
                    <p id="current-status-display" class="text-white text-sm font-medium tracking-wide">{{ __($transaction->laundry_status) }}</p>
                </div>
            </div>
        </div>

        <div class="card-glass rounded-xl p-8 md:p-12 border-zinc-800 shadow-2xl scroll-animate delay-100">
            @php
                $statusImages = [
                    'Pending Pickup'         => 'tracking/picking.jpeg',
                    'Sorting & Inspection'   => 'tracking/sorter.jpeg',
                    'Artisanal Wash'         => 'tracking/washer.jpeg',
                    'Professional Pressing'  => 'tracking/presser.jpeg',
                    'Final Packaging'        => 'tracking/packer.jpeg',
                    'Outbound Delivery'      => 'tracking/deliver.jpeg',
                    'Pending Drop-off'       => 'tracking/picking.jpeg',
                    'Received & Sorted'      => 'tracking/sorter.jpeg',
                    'Deep Cleaning'          => 'tracking/washer.jpeg',
                    'Artisanal Pressing'     => 'tracking/presser.jpeg',
                    'Completed'              => 'tracking/deliver.jpeg',
                ];
                $currentImagePath = $statusImages[$transaction->laundry_status] ?? 'tracking/picking.jpeg';
            @endphp

            <!-- Centered "Ball Size" Circular Visual -->
            <div class="flex justify-center mb-8">
                <div class="relative w-20 h-20 rounded-full overflow-hidden border-2 border-zinc-800 shadow-[0_0_30px_rgba(0,0,0,0.5)] group">
                    <img id="status-visual" 
                         src="{{ Storage::url($currentImagePath) }}" 
                         alt="Service Visual" 
                         class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/40 via-transparent to-transparent"></div>
                </div>
            </div>

            <!-- Timeline Tracker -->
            <div class="relative">
                @php
                    $currentIndex = array_search($transaction->laundry_status, $statuses);
                    if ($currentIndex === false) $currentIndex = 0;
                @endphp

                <!-- Vertical Line (Mobile) / Horizontal Line (Desktop) -->
                <div class="absolute left-4 md:left-0 md:top-5 md:w-full h-full md:h-0.5 bg-zinc-800 z-0"></div>
                
                <div class="flex flex-col md:flex-row justify-between relative z-10 gap-8 md:gap-4">
                    @foreach($statuses as $index => $status)
                        <div class="flex flex-row md:flex-col items-center gap-4 md:gap-0 md:text-center flex-1">
                            <!-- Step Indicator -->
                            <div class="relative">
                                <div id="step-circle-{{ $index }}" class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-700 border-2 
                                    @if($index < $currentIndex) 
                                        bg-amber-500 border-amber-500 text-zinc-950
                                    @elseif($index === $currentIndex)
                                        bg-amber-500 border-amber-500 text-zinc-950 shadow-[0_0_20px_rgba(212,175,55,0.4)]
                                    @else
                                        bg-zinc-950 border-zinc-800 text-zinc-600
                                    @endif">
                                    
                                    <div id="step-content-{{ $index }}">
                                        @if($index < $currentIndex || ($index === $currentIndex && ($status === 'Outbound Delivery' || $status === 'Completed')))
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div id="step-ping-{{ $index }}" class="absolute inset-0 rounded-full animate-ping bg-amber-500/20 @if($index !== $currentIndex) hidden @endif"></div>
                            </div>

                            <!-- Step Label -->
                            <div class="md:mt-4">
                                <p id="step-label-{{ $index }}" class="text-[10px] font-bold uppercase tracking-widest transition-colors duration-500
                                    @if($index <= $currentIndex) text-white @else text-zinc-600 @endif">
                                    {{ __($status) }}
                                </p>
                                <p id="step-active-{{ $index }}" class="text-[8px] text-amber-500 font-medium uppercase tracking-[0.2em] mt-1 @if($index !== $currentIndex) hidden @endif">
                                    {{ __('Active Step') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Simulation Badge -->
        <div class="mt-8 flex justify-center gap-4">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500/5 border border-amber-500/20 rounded-full">
                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                <span class="text-[10px] text-amber-500 font-bold uppercase tracking-[0.2em]">{{ __('Auto-Simulate Mode Active') }}</span>
            </div>

            @if($transaction->is_priority)
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.2)]">
                    <span class="text-xs">⚡</span>
                    <span class="text-[10px] text-blue-400 font-bold uppercase tracking-[0.2em]">{{ __('ASAP Priority') }}</span>
                </div>
            @endif
        </div>

        <!-- Breakdown Section -->
        <div class="mt-12 card-glass rounded-xl border-zinc-800/50 p-6 scroll-animate delay-150">
            <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-500 mb-6 border-b border-zinc-800 pb-4">{{ __('Order Breakdown') }}</h3>
            
            @if($transaction->total_price <= 0)
                <div class="py-12 text-center">
                    <div class="inline-flex items-center gap-3 px-6 py-3 bg-amber-500/10 border border-amber-500/20 rounded-lg">
                        <svg class="w-5 h-5 text-amber-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                        <span class="text-sm font-bold text-amber-500 uppercase tracking-widest">{{ __('Total: Pending Final Weigh-in at Facility') }}</span>
                    </div>
                    <p class="mt-4 text-zinc-500 text-[10px] uppercase tracking-[0.3em]">{{ __('Our artisans are currently inspecting your garments') }}</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($transaction->details as $detail)
                        <div class="flex justify-between items-center group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-sm bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-500">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white tracking-wide uppercase">{{ $detail->service->name }}</p>
                                    <p class="text-[10px] text-zinc-500 uppercase tracking-widest">
                                        @if($detail->service->unit_type === 'kg')
                                            {{ __('Weight:') }} {{ number_format($detail->weight, 1) }} Kg
                                        @else
                                            {{ __('Quantity:') }} {{ number_format($detail->weight, 0) }} Pcs
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-mono text-zinc-300">Rp {{ number_format($detail->weight * $detail->unit_price, 0, ',', '.') }}</p>
                                <p class="text-[9px] text-zinc-600 uppercase tracking-widest">Rp {{ number_format($detail->unit_price, 0, ',', '.') }} / {{ $detail->service->unit_type === 'kg' ? 'Kg' : 'Pcs' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-8 border-t border-zinc-800 flex flex-col gap-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-[0.2em]">{{ __('Official Intake Volume') }}</span>
                        <span class="text-sm font-bold text-white uppercase tracking-widest">
                            @php
                                $kgTotal = $transaction->details->where('service.unit_type', 'kg')->sum('weight');
                                $pcsTotal = $transaction->details->where('service.unit_type', '!=', 'kg')->sum('weight');
                            @endphp
                            @if($kgTotal > 0) {{ number_format($kgTotal, 1) }} Kg @endif
                            @if($kgTotal > 0 && $pcsTotal > 0) + @endif
                            @if($pcsTotal > 0) {{ number_format($pcsTotal, 0) }} Pcs @endif
                        </span>
                    </div>

                    @if($transaction->delivery_fee > 0)
                        <div class="flex justify-between items-center text-zinc-300">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em]">{{ __('Delivery & Transport') }}</span>
                            <span class="text-sm font-mono font-bold">+ Rp {{ number_format($transaction->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if($transaction->asap_surcharge > 0)
                        <div class="flex justify-between items-center text-amber-500">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em]">{{ __('ASAP Priority Surcharge') }}</span>
                            <span class="text-sm font-mono font-bold">+ Rp {{ number_format($transaction->asap_surcharge, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if($transaction->promo_discount > 0)
                        {{-- Total Before Promo --}}
                        <div class="mt-3 pt-3 border-t border-zinc-800 flex justify-between items-center">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">{{ __('Total Before Promo') }}</span>
                            <span class="text-sm font-mono font-bold text-zinc-300">Rp {{ number_format($transaction->total_price + $transaction->promo_discount, 0, ',', '.') }}</span>
                        </div>

                        {{-- Promo Cut --}}
                        <div class="flex justify-between items-center text-emerald-400">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em]">{{ __('Promo Applied') }}</span>
                            <span class="text-sm font-mono font-bold">- Rp {{ number_format($transaction->promo_discount, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-t border-zinc-800 flex justify-between items-end">
                        <div>
                            <span class="text-xs font-bold text-white uppercase tracking-[0.3em]">{{ __('Final Invoice') }}</span>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-2xl font-serif font-bold text-amber-500">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                <span class="px-2 py-0.5 rounded-sm text-[8px] font-bold uppercase tracking-widest border {{ $transaction->payment_status === 'Paid' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500' : 'bg-red-500/10 border-red-500/20 text-red-500' }}">
                                    {{ __('Status:') }} {{ __($transaction->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

{{-- ─── REVIEW MODAL (Moved outside main content to prevent z-index "sinking") ─── --}}
<div id="review-modal" class="fixed inset-0 flex items-center justify-center p-4 hidden opacity-0 transition-all duration-500" style="z-index: 99999; background: rgba(0,0,0,0.98); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);">
    <div class="relative w-full max-w-xl card-glass p-10 md:p-14 border-amber-500/20 shadow-[0_0_100px_rgba(0,0,0,1)] rounded-3xl transform scale-95 transition-all duration-500" id="review-content" style="background: #111113; border: 1px solid rgba(212,175,55,0.15);">
        
        <div class="text-center mb-10">
            <div class="w-20 h-20 luxury-bg rounded-full flex items-center justify-center mx-auto mb-8 shadow-[0_0_40px_rgba(212,175,55,0.4)]">
                <svg class="w-10 h-10 text-zinc-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-4xl font-serif font-bold text-white mb-3 tracking-tight">{{ __('Service Perfected') }}</h2>
            <p class="text-zinc-400 text-base tracking-wide max-w-sm mx-auto leading-relaxed">{{ __('Your garments have been returned to their prime. How was your experience with the Felix artisanal team?') }}</p>
        </div>

        <form action="{{ route('public.order.review') }}" method="POST" class="space-y-10">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
            
            {{-- Star Rating --}}
            <div class="flex flex-col items-center gap-4">
                <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-amber-500/80">{{ __('Artisanal Rating') }}</p>
                <div class="flex gap-3">
                    @foreach(range(1, 5) as $i)
                        <button type="button" onclick="setRating({{ $i }})" class="star-btn p-1 transition-all duration-300 transform hover:scale-125" data-value="{{ $i }}">
                            <svg id="star-{{ $i }}" class="w-12 h-12 text-zinc-800 transition-colors filter drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="rating" id="rating-input" value="5" required>
            </div>

            {{-- Comment --}}
            <div class="space-y-3">
                <label for="comment" class="block text-[11px] font-bold uppercase tracking-[0.2em] text-zinc-500 px-1">{{ __('Any advice for our curators?') }}</label>
                <textarea name="comment" id="comment" rows="4" class="w-full bg-zinc-900/80 border border-zinc-800 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all placeholder-zinc-700 resize-none text-sm leading-relaxed" placeholder="{{ __('e.g. The attention to detail was exceptional...') }}"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full luxury-bg text-zinc-950 py-5 rounded-xl text-sm font-bold uppercase tracking-[0.2em] transition-all hover:brightness-110 hover:shadow-[0_0_30px_rgba(212,175,55,0.3)] active:scale-[0.98]">
                    {{ __('Finalize & Return Home') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Rating Logic
    function setRating(val) {
        document.getElementById('rating-input').value = val;
        for (let i = 1; i <= 5; i++) {
            const star = document.getElementById(`star-${i}`);
            if (i <= val) {
                star.classList.remove('text-zinc-800');
                star.classList.add('text-amber-500');
            } else {
                star.classList.remove('text-amber-500');
                star.classList.add('text-zinc-800');
            }
        }
    }

    // Initial rating (5 stars)
    setRating(5);

    function showReviewModal() {
        const modal = document.getElementById('review-modal');
        const content = document.getElementById('review-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const transactionId = "{{ $transaction->id }}";
        const statuses = @json($statuses);
        let currentIndex = {{ $currentIndex }};
        
        // If already completed and NO review yet, show modal immediately
        const hasReview = @json($hasReview);
        if ((statuses[currentIndex] === 'Outbound Delivery' || statuses[currentIndex] === 'Completed') && !hasReview) {
            setTimeout(showReviewModal, 1000);
            return;
        }

        const simulationInterval = setInterval(async () => {
            if (currentIndex >= statuses.length - 1) {
                clearInterval(simulationInterval);
                return;
            }

            try {
                const response = await fetch(`/api/demo/advance-status/${transactionId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    const newStatus = data.status;
                    const nextIndex = statuses.indexOf(newStatus);

                    if (nextIndex > currentIndex) {
                        updateUI(currentIndex, nextIndex);
                        currentIndex = nextIndex;
                        
                        document.getElementById('current-status-display').innerText = newStatus;

                        // Update Status Circle
                        const statusImages = @json($statusImages);
                        const visualImg = document.getElementById('status-visual');
                        if (visualImg && statusImages[newStatus]) {
                            visualImg.style.opacity = '0';
                            visualImg.style.transform = 'scale(0.9)';
                            setTimeout(() => {
                                visualImg.src = `/storage/${statusImages[newStatus]}`;
                                visualImg.style.opacity = '1';
                                visualImg.style.transform = 'scale(1)';
                            }, 500);
                        }

                        if (data.is_completed && !hasReview) {
                            clearInterval(simulationInterval);
                            setTimeout(showReviewModal, 2000);
                        }
                    }
                }
            } catch (error) {
                console.error('Simulation error:', error);
            }
        }, 3500);

        function updateUI(oldIdx, newIdx) {
            // Update old current step to "Completed" (check mark)
            const oldCircle = document.getElementById(`step-circle-${oldIdx}`);
            const oldContent = document.getElementById(`step-content-${oldIdx}`);
            const oldPing = document.getElementById(`step-ping-${oldIdx}`);
            const oldActiveLabel = document.getElementById(`step-active-${oldIdx}`);

            oldCircle.className = "w-8 h-8 rounded-full flex items-center justify-center transition-all duration-700 border-2 bg-amber-500 border-amber-500 text-zinc-950";
            oldContent.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
            oldPing.classList.add('hidden');
            oldActiveLabel.classList.add('hidden');

            // Update new step to "Active"
            const newCircle = document.getElementById(`step-circle-${newIdx}`);
            const newContent = document.getElementById(`step-content-${newIdx}`);
            const newLabel = document.getElementById(`step-label-${newIdx}`);
            const newPing = document.getElementById(`step-ping-${newIdx}`);
            const newActiveLabel = document.getElementById(`step-active-${newIdx}`);

            newCircle.className = "w-8 h-8 rounded-full flex items-center justify-center transition-all duration-700 border-2 bg-amber-500 border-amber-500 text-zinc-950 shadow-[0_0_20px_rgba(212,175,55,0.4)]";
            
            // If the NEW step is the final one, show the checkmark immediately
            if (statuses[newIdx] === 'Outbound Delivery' || statuses[newIdx] === 'Completed') {
                newContent.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
            }

            newLabel.classList.remove('text-zinc-600');
            newLabel.classList.add('text-white');
            newPing.classList.remove('hidden');
            newActiveLabel.classList.remove('hidden');
        }
    });
</script>
@endpush
