@php $d = $this->getData(); @endphp

<div style="display:flex;flex-direction:column;gap:16px;">

    {{-- ── HERO BANNER ────────────────────────────────────────────────── --}}
    <div style="position:relative;overflow:hidden;border-radius:16px;height:380px;background:#09090b;box-shadow:0 20px 60px rgba(0,0,0,0.5);">

        <img src="{{ asset('test/images/1.png') }}"
            style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:top center;opacity:0.78;">

        <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(0,0,0,0.82) 0%,rgba(0,0,0,0.25) 55%,rgba(0,0,0,0.65) 100%);"></div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:55%;background:linear-gradient(to top,rgba(9,9,11,1),transparent);"></div>
        <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,transparent,#d4af22 30%,#f59e0b 50%,#d4af22 70%,transparent);"></div>

        <div style="position:relative;z-index:2;height:100%;display:flex;flex-direction:column;justify-content:space-between;padding:2rem 2.5rem;">

            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="color:#f59e0b;font-size:10px;font-weight:700;letter-spacing:0.35em;text-transform:uppercase;margin:0 0 8px;">Felix Elite Garment Care &nbsp;·&nbsp; Admin Panel</p>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:7px;height:7px;border-radius:50%;background:#22c55e;box-shadow:0 0 10px #22c55e;"></div>
                        <span style="color:#86efac;font-size:12px;font-weight:500;">All Systems Operational</span>
                    </div>
                </div>
                <div style="background:rgba(0,0,0,0.55);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:12px 18px;backdrop-filter:blur(12px);text-align:right;">
                    <p style="color:#52525b;font-size:10px;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 4px;">Administrator</p>
                    <p style="color:#fff;font-size:15px;font-weight:600;margin:0;">{{ auth('admin')->user()?->name }}</p>
                    <p style="color:#71717a;font-size:11px;margin:3px 0 0;">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>

            <div>
                <h1 style="color:#fff;font-size:clamp(2.8rem,5vw,4.5rem);font-weight:700;font-family:Georgia,serif;margin:0;line-height:1;text-shadow:0 4px 30px rgba(0,0,0,0.7);">
                    Admin Dashboard
                </h1>
                <div style="width:72px;height:3px;background:linear-gradient(90deg,#f59e0b,#d4af22);border-radius:2px;margin-top:14px;"></div>
            </div>

            {{-- Stats row --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
                @foreach([
                    ['label'=>'Total Orders', 'val'=>$d['orders'],                                  'color'=>'#e4e4e7'],
                    ['label'=>'In Progress',  'val'=>$d['active'],                                  'color'=>'#f59e0b'],
                    ['label'=>'Revenue',      'val'=>'Rp '.number_format($d['revenue']/1000000,1).'M', 'color'=>'#4ade80'],
                    ['label'=>'B2B Partners', 'val'=>$d['partners'],                                'color'=>'#60a5fa'],
                ] as $s)
                <div style="background:rgba(0,0,0,0.65);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.07);border-radius:10px;padding:12px 16px;min-width:0;overflow:hidden;">
                    <p style="color:#52525b;font-size:9px;text-transform:uppercase;letter-spacing:0.18em;margin:0 0 6px;white-space:nowrap;">{{ $s['label'] }}</p>
                    <p style="color:{{ $s['color'] }};font-size:1.6rem;font-weight:700;margin:0;line-height:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $s['val'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── KPI CARDS ────────────────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
        @foreach([
            ['icon'=>'⏳','label'=>'Pending',         'val'=>$d['pending'],   'color'=>'#f59e0b','border'=>'rgba(245,158,11,0.2)'],
            ['icon'=>'👥','label'=>'Customers',        'val'=>$d['customers'], 'color'=>'#e4e4e7','border'=>'rgba(228,228,231,0.1)'],
            ['icon'=>'🏢','label'=>'Corporate Orders', 'val'=>$d['corporate'], 'color'=>'#60a5fa','border'=>'rgba(59,130,246,0.2)'],
            ['icon'=>'💰','label'=>'Total Revenue',    'val'=>'Rp '.number_format($d['revenue'],0,',','.'), 'color'=>'#4ade80','border'=>'rgba(34,197,94,0.2)'],
        ] as $k)
        <div style="background:#18181b;border:1px solid {{ $k['border'] }};border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
            <div style="font-size:1.8rem;line-height:1;flex-shrink:0;">{{ $k['icon'] }}</div>
            <div>
                <p style="color:#52525b;font-size:10px;text-transform:uppercase;letter-spacing:0.15em;margin:0 0 5px;">{{ $k['label'] }}</p>
                <p style="color:{{ $k['color'] }};font-size:1.4rem;font-weight:700;margin:0;line-height:1;">{{ $k['val'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── RECENT ORDERS ─────────────────────────────────────────────── --}}
    <div style="background:#18181b;border:1px solid #27272a;border-radius:14px;overflow:hidden;">
        <div style="padding:18px 24px;border-bottom:1px solid #27272a;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p style="color:#fff;font-weight:600;font-size:15px;margin:0;">Recent Transactions</p>
                <p style="color:#52525b;font-size:11px;margin:3px 0 0;">Latest 5 orders across all channels</p>
            </div>
            <a href="/laundry/transactions" style="color:#f59e0b;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;text-decoration:none;border:1px solid rgba(245,158,11,0.3);padding:7px 16px;border-radius:7px;background:rgba(245,158,11,0.05);">
                View All →
            </a>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#09090b;">
                    <th style="color:#3f3f46;font-size:10px;text-transform:uppercase;letter-spacing:0.18em;padding:12px 24px;text-align:left;font-weight:600;">Invoice</th>
                    <th style="color:#3f3f46;font-size:10px;text-transform:uppercase;letter-spacing:0.18em;padding:12px 24px;text-align:left;font-weight:600;">Customer</th>
                    <th style="color:#3f3f46;font-size:10px;text-transform:uppercase;letter-spacing:0.18em;padding:12px 24px;text-align:left;font-weight:600;">Service</th>
                    <th style="color:#3f3f46;font-size:10px;text-transform:uppercase;letter-spacing:0.18em;padding:12px 24px;text-align:left;font-weight:600;">Status</th>
                    <th style="color:#3f3f46;font-size:10px;text-transform:uppercase;letter-spacing:0.18em;padding:12px 24px;text-align:right;font-weight:600;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($d['recent'] as $order)
                @php
                    $sc = match($order->laundry_status) {
                        'Completed'  => ['rgba(34,197,94,0.1)',  '#4ade80',  'rgba(34,197,94,0.3)'],
                        'Processing' => ['rgba(59,130,246,0.1)', '#60a5fa',  'rgba(59,130,246,0.3)'],
                        'Ready'      => ['rgba(245,158,11,0.1)', '#f59e0b',  'rgba(245,158,11,0.3)'],
                        'Cancelled'  => ['rgba(239,68,68,0.1)',  '#f87171',  'rgba(239,68,68,0.3)'],
                        default      => ['rgba(82,82,91,0.1)',   '#a1a1aa',  'rgba(82,82,91,0.3)'],
                    };
                @endphp
                <tr style="border-bottom:1px solid #1f1f23;transition:background 0.15s;">
                    <td style="padding:16px 24px;"><span style="color:#71717a;font-family:monospace;font-size:12px;">{{ $order->invoice_code }}</span></td>
                    <td style="padding:16px 24px;"><span style="color:#d4d4d8;font-size:13px;">{{ $order->customer_name ?? $order->customer?->name ?? '—' }}</span></td>
                    <td style="padding:16px 24px;"><span style="color:#a1a1aa;font-size:13px;">{{ $order->tier_level }}</span></td>
                    <td style="padding:16px 24px;">
                        <span style="background:{{ $sc[0] }};color:{{ $sc[1] }};border:1px solid {{ $sc[2] }};border-radius:6px;padding:4px 12px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">
                            {{ $order->laundry_status }}
                        </span>
                    </td>
                    <td style="padding:16px 24px;text-align:right;">
                        @if($order->total_price > 0)
                            <span style="color:#f59e0b;font-weight:700;font-size:13px;">Rp {{ number_format($order->total_price,0,',','.') }}</span>
                        @else
                            <span style="color:#3f3f46;font-size:12px;font-style:italic;">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding:32px;text-align:center;color:#3f3f46;font-style:italic;">No transactions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
