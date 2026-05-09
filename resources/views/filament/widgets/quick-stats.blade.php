@php $data = $this->getData(); @endphp

<div style="display:flex;flex-direction:column;gap:20px;">

    {{-- KPI Cards Row --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">

        @foreach([
            ['label'=>'Pending Orders',   'value'=>$data['pending'],                               'color'=>'#f59e0b', 'border'=>'rgba(245,158,11,0.2)',  'icon'=>'⏳'],
            ['label'=>'Total Revenue',    'value'=>'Rp '.number_format($data['revenue'],0,',','.'), 'color'=>'#4ade80', 'border'=>'rgba(34,197,94,0.2)',   'icon'=>'💰'],
            ['label'=>'Registered Users', 'value'=>$data['customers'],                              'color'=>'#e4e4e7', 'border'=>'rgba(228,228,231,0.1)', 'icon'=>'👤'],
            ['label'=>'Corporate Orders', 'value'=>$data['corporate'],                              'color'=>'#60a5fa', 'border'=>'rgba(59,130,246,0.2)',  'icon'=>'🏢'],
        ] as $kpi)
        <div style="background:#18181b;border:1px solid {{ $kpi['border'] }};border-radius:12px;padding:20px 22px;display:flex;align-items:center;gap:16px;">
            <div style="font-size:1.8rem;line-height:1;">{{ $kpi['icon'] }}</div>
            <div>
                <p style="color:#52525b;font-size:10px;text-transform:uppercase;letter-spacing:0.15em;margin:0 0 4px;">{{ $kpi['label'] }}</p>
                <p style="color:{{ $kpi['color'] }};font-size:1.5rem;font-weight:700;margin:0;line-height:1;">{{ $kpi['value'] }}</p>
            </div>
        </div>
        @endforeach

    </div>

    {{-- Recent Orders Table --}}
    <div style="background:#18181b;border:1px solid #27272a;border-radius:12px;overflow:hidden;">
        <div style="padding:16px 22px;border-bottom:1px solid #27272a;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p style="color:#fff;font-weight:600;font-size:14px;margin:0;">Recent Orders</p>
                <p style="color:#52525b;font-size:11px;margin:2px 0 0;">Latest 5 transactions</p>
            </div>
            <a href="/laundry/transactions" style="color:#f59e0b;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;border:1px solid rgba(245,158,11,0.3);padding:6px 14px;border-radius:6px;">
                View All →
            </a>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#09090b;">
                    <th style="color:#52525b;font-size:10px;text-transform:uppercase;letter-spacing:0.15em;padding:12px 22px;text-align:left;font-weight:600;">Invoice</th>
                    <th style="color:#52525b;font-size:10px;text-transform:uppercase;letter-spacing:0.15em;padding:12px 22px;text-align:left;font-weight:600;">Customer</th>
                    <th style="color:#52525b;font-size:10px;text-transform:uppercase;letter-spacing:0.15em;padding:12px 22px;text-align:left;font-weight:600;">Tier</th>
                    <th style="color:#52525b;font-size:10px;text-transform:uppercase;letter-spacing:0.15em;padding:12px 22px;text-align:left;font-weight:600;">Status</th>
                    <th style="color:#52525b;font-size:10px;text-transform:uppercase;letter-spacing:0.15em;padding:12px 22px;text-align:right;font-weight:600;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['recent'] as $order)
                @php
                    $statusColor = match($order->laundry_status) {
                        'Completed'  => ['bg'=>'rgba(34,197,94,0.1)',  'text'=>'#4ade80',  'border'=>'rgba(34,197,94,0.3)'],
                        'Processing' => ['bg'=>'rgba(59,130,246,0.1)', 'text'=>'#60a5fa',  'border'=>'rgba(59,130,246,0.3)'],
                        'Ready'      => ['bg'=>'rgba(245,158,11,0.1)', 'text'=>'#f59e0b',  'border'=>'rgba(245,158,11,0.3)'],
                        'Cancelled'  => ['bg'=>'rgba(239,68,68,0.1)',  'text'=>'#f87171',  'border'=>'rgba(239,68,68,0.3)'],
                        default      => ['bg'=>'rgba(113,113,122,0.1)','text'=>'#a1a1aa',  'border'=>'rgba(113,113,122,0.3)'],
                    };
                @endphp
                <tr style="border-bottom:1px solid #1f1f23;">
                    <td style="padding:14px 22px;">
                        <span style="color:#a1a1aa;font-family:monospace;font-size:12px;">{{ $order->invoice_code }}</span>
                    </td>
                    <td style="padding:14px 22px;">
                        <span style="color:#e4e4e7;font-size:13px;">{{ $order->customer?->name ?? '—' }}</span>
                    </td>
                    <td style="padding:14px 22px;">
                        <span style="color:#e4e4e7;font-size:13px;">{{ $order->tier_level }}</span>
                    </td>
                    <td style="padding:14px 22px;">
                        <span style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['text'] }};border:1px solid {{ $statusColor['border'] }};border-radius:6px;padding:3px 10px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">
                            {{ $order->laundry_status }}
                        </span>
                    </td>
                    <td style="padding:14px 22px;text-align:right;">
                        @if($order->total_price > 0)
                            <span style="color:#f59e0b;font-weight:600;font-size:13px;">Rp {{ number_format($order->total_price,0,',','.') }}</span>
                        @else
                            <span style="color:#3f3f46;font-size:12px;font-style:italic;">Pending weigh-in</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:32px;text-align:center;color:#3f3f46;font-style:italic;font-size:13px;">No transactions yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
