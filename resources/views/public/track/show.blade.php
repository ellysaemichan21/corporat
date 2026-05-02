<x-layouts.public :title="'Tracking ' . $transaction->invoice_code">

    <!-- Invoice hero -->
    <div class="invoice-hero">
        <div style="font-size:.82rem;opacity:.75;margin-bottom:.25rem;">Order Tracking</div>
        <div class="invoice-code">{{ $transaction->invoice_code }}</div>
        <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem;">
            @php
                $ls = $transaction->laundry_status;
                $ps = $transaction->payment_status;
                $lsBadge = match(strtolower($ls)) {
                    'pending' => 'badge-pending',
                    'processing' => 'badge-processing',
                    'ready' => 'badge-ready',
                    'done' => 'badge-done',
                    default => 'badge-done',
                };
                $psBadge = strtolower($ps) === 'paid' ? 'badge-paid' : 'badge-unpaid';
                $statusOrder = ['Pending', 'Processing', 'Ready', 'Done'];
                $currentIdx = array_search($ls, $statusOrder) !== false ? array_search($ls, $statusOrder) : 0;
            @endphp
            <span class="badge {{ $lsBadge }}">🧺 {{ $ls }}</span>
            <span class="badge {{ $psBadge }}">💳 {{ $ps }}</span>
            <span class="badge {{ ($transaction->order_channel ?? 'Self') === 'Online' ? 'badge-online' : 'badge-self' }}">
                {{ ($transaction->order_channel ?? 'Self') === 'Online' ? '🌐 Online' : '🏠 Self' }}
            </span>
        </div>
        @if (!empty($transaction->pickup_note))
            <div style="margin-top:.75rem;font-size:.82rem;opacity:.8;">
                📝 {{ $transaction->pickup_note }}
            </div>
        @endif
    </div>

    <div class="grid-3" style="margin-bottom:1.25rem;align-items:start;">

        {{-- Left: Items + Total --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            <div class="card">
                <div class="card-title"><span class="icon">🧺</span> Order Items</div>
                @foreach ($transaction->details as $detail)
                    <div class="service-row">
                        <div>
                            <div class="service-name">{{ $detail->service->name ?? 'Service' }}</div>
                            <div class="service-price">Qty {{ (float) $detail->qty }}</div>
                        </div>
                        <div style="font-size:.875rem;font-weight:700;color:#111827;">
                            Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach

                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #F3F4F6;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:.85rem;color:#6B7280;">Total</span>
                    <span style="font-size:1.2rem;font-weight:800;color:#1e1b4b;">
                        Rp {{ number_format((float) $transaction->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Status timeline --}}
            <div class="card">
                <div class="card-title"><span class="icon">📍</span> Progress</div>
                <div class="timeline">
                    @foreach ($statusOrder as $idx => $step)
                        <div class="tl-item">
                            <div class="tl-line">
                                <div class="tl-dot {{ $idx <= $currentIdx ? 'done' : '' }}"></div>
                                @if (!$loop->last)
                                    <div class="tl-connector"></div>
                                @endif
                            </div>
                            <div>
                                <div class="tl-label" style="{{ $idx === $currentIdx ? 'color:#6C63FF;' : '' }}">
                                    {{ $step }}
                                    @if ($idx === $currentIdx)
                                        <span style="font-size:.7rem;color:#6C63FF;margin-left:.35rem;">← current</span>
                                    @endif
                                </div>
                                <div class="tl-sub">
                                    @if ($idx < $currentIdx) Completed
                                    @elseif ($idx === $currentIdx) In progress
                                    @else Upcoming
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Customer info --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">
            <div class="card">
                <div class="card-title"><span class="icon">👤</span> Customer</div>
                <div style="font-size:.875rem;">
                    <div style="font-weight:700;color:#111827;">{{ $transaction->customer->name }}</div>
                    <div style="color:#6B7280;margin-top:.2rem;">{{ $transaction->customer->phone }}</div>
                    @if ($transaction->customer->address)
                        <div style="color:#6B7280;margin-top:.5rem;font-size:.8rem;line-height:1.5;">
                            📍 {{ $transaction->customer->address }}
                        </div>
                    @endif
                </div>
                <div style="margin-top:.875rem;padding-top:.875rem;border-top:1px solid #F3F4F6;">
                    <div style="font-size:.75rem;color:#9CA3AF;">Tier</div>
                    <div style="font-weight:700;font-size:.9rem;color:#1e1b4b;margin-top:.15rem;">
                        {{ $transaction->tier_level }}
                    </div>
                </div>
            </div>

            {{-- Images if any --}}
            @if ($transaction->images && $transaction->images->count())
                <div class="card">
                    <div class="card-title"><span class="icon">📷</span> Photos</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;">
                        @foreach ($transaction->images as $img)
                            <img src="{{ Storage::url($img->path) }}" alt="Laundry image"
                                 style="border-radius:8px;width:100%;object-fit:cover;aspect-ratio:1;" />
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Logs if any --}}
            @if ($transaction->logs && $transaction->logs->count())
                <div class="card">
                    <div class="card-title"><span class="icon">📋</span> History</div>
                    @foreach ($transaction->logs->sortByDesc('created_at') as $log)
                        <div style="font-size:.8rem;padding:.5rem 0;border-bottom:1px solid #F3F4F6;">
                            <div style="font-weight:600;color:#374151;">{{ $log->message ?? $log->status ?? '—' }}</div>
                            <div style="color:#9CA3AF;margin-top:.1rem;">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:.875rem;flex-wrap:wrap;">
        <a class="btn-ghost" href="{{ route('public.track.form') }}">
            🔍 Track Another Order
        </a>
        <a class="btn-ghost" href="{{ route('public.order.create') }}">
            ➕ New Order
        </a>
    </div>

</x-layouts.public>
