<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'Laundry') }} — {{ config('app.name', 'Laundry') }}</title>
        <meta name="description" content="Place a laundry order or track your existing order easily online.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --brand: #6C63FF;
                --brand-dark: #4F46E5;
                --brand-light: #EEF2FF;
                --surface: #F8F8FF;
            }

            * { box-sizing: border-box; }

            body {
                font-family: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif;
                background: var(--surface);
                min-height: 100vh;
                margin: 0;
            }

            /* ── Header ── */
            .pub-header {
                background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
                padding: 0 1.5rem;
                position: sticky;
                top: 0;
                z-index: 50;
                box-shadow: 0 4px 24px rgba(99,63,255,.25);
            }

            .pub-header-inner {
                max-width: 68rem;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 64px;
            }

            .pub-logo {
                display: flex;
                align-items: center;
                gap: .625rem;
                text-decoration: none;
            }

            .pub-logo-icon {
                width: 34px;
                height: 34px;
                background: linear-gradient(135deg, #a5b4fc, #818cf8);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                flex-shrink: 0;
            }

            .pub-logo-name {
                font-size: 1.05rem;
                font-weight: 700;
                color: #fff;
                letter-spacing: -.02em;
            }

            /* ── Tab nav ── */
            .pub-tab-nav {
                display: flex;
                align-items: center;
                gap: .25rem;
            }

            .pub-tab {
                display: inline-flex;
                align-items: center;
                gap: .45rem;
                padding: .45rem 1rem;
                border-radius: 8px;
                font-size: .85rem;
                font-weight: 600;
                color: #c7d2fe;
                text-decoration: none;
                transition: all .18s ease;
                position: relative;
            }

            .pub-tab:hover {
                color: #fff;
                background: rgba(255,255,255,.1);
            }

            .pub-tab.active {
                color: #fff;
                background: rgba(255,255,255,.15);
                box-shadow: inset 0 0 0 1px rgba(255,255,255,.2);
            }

            .pub-tab .tab-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: #a5b4fc;
                display: none;
            }

            .pub-tab.active .tab-dot {
                display: block;
                background: #6ee7b7;
            }

            /* ── Main ── */
            .pub-main {
                max-width: 68rem;
                margin: 0 auto;
                padding: 2rem 1.5rem 4rem;
            }

            /* ── Page header card ── */
            .page-hero {
                background: linear-gradient(135deg, #6C63FF 0%, #4F46E5 100%);
                border-radius: 16px;
                padding: 1.75rem 2rem;
                margin-bottom: 1.75rem;
                color: #fff;
                position: relative;
                overflow: hidden;
            }

            .page-hero::before {
                content: '';
                position: absolute;
                top: -40px; right: -40px;
                width: 140px; height: 140px;
                border-radius: 50%;
                background: rgba(255,255,255,.08);
            }

            .page-hero::after {
                content: '';
                position: absolute;
                bottom: -60px; right: 60px;
                width: 180px; height: 180px;
                border-radius: 50%;
                background: rgba(255,255,255,.05);
            }

            .page-hero h1 {
                font-size: 1.4rem;
                font-weight: 800;
                margin: 0 0 .35rem;
                letter-spacing: -.02em;
            }

            .page-hero p {
                font-size: .9rem;
                opacity: .85;
                margin: 0;
            }

            /* ── Error alert ── */
            .err-alert {
                background: #FEF2F2;
                border: 1px solid #FECACA;
                border-radius: 12px;
                padding: 1rem 1.25rem;
                font-size: .875rem;
                margin-bottom: 1.5rem;
                color: #991B1B;
            }

            .err-alert-title {
                font-weight: 700;
                margin-bottom: .35rem;
            }

            .err-alert ul {
                margin: 0;
                padding-left: 1.25rem;
            }

            /* ── Cards ── */
            .card {
                background: #fff;
                border: 1px solid #E5E7EB;
                border-radius: 14px;
                padding: 1.25rem 1.5rem;
                box-shadow: 0 1px 6px rgba(0,0,0,.04);
            }

            .card-title {
                font-size: .9rem;
                font-weight: 700;
                color: #1e1b4b;
                margin: 0 0 1rem;
                display: flex;
                align-items: center;
                gap: .5rem;
            }

            .card-title .icon {
                width: 26px;
                height: 26px;
                background: var(--brand-light);
                border-radius: 7px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 13px;
            }

            /* ── Form elements ── */
            .form-input {
                width: 100%;
                border: 1.5px solid #E5E7EB;
                border-radius: 9px;
                padding: .6rem .875rem;
                font-size: .875rem;
                font-family: inherit;
                outline: none;
                transition: border-color .18s, box-shadow .18s;
                background: #FAFAFA;
                color: #111827;
            }

            .form-input:focus {
                border-color: var(--brand);
                box-shadow: 0 0 0 3px rgba(108,99,255,.12);
                background: #fff;
            }

            .form-label {
                font-size: .8rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: .35rem;
                display: block;
            }

            /* ── Radio pills ── */
            .radio-group {
                display: flex;
                flex-wrap: wrap;
                gap: .625rem;
            }

            .radio-pill {
                display: inline-flex;
                align-items: center;
                gap: .5rem;
                border: 1.5px solid #E5E7EB;
                border-radius: 9px;
                padding: .55rem 1rem;
                font-size: .85rem;
                font-weight: 500;
                cursor: pointer;
                transition: all .18s;
                background: #FAFAFA;
                color: #374151;
                user-select: none;
            }

            .radio-pill input[type="radio"] { display: none; }

            .radio-pill:has(input:checked) {
                border-color: var(--brand);
                background: var(--brand-light);
                color: var(--brand-dark);
                font-weight: 700;
                box-shadow: 0 0 0 2px rgba(108,99,255,.15);
            }

            .radio-pill .pill-check {
                width: 16px; height: 16px;
                border: 2px solid #D1D5DB;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                transition: all .18s;
            }

            .radio-pill:has(input:checked) .pill-check {
                border-color: var(--brand);
                background: var(--brand);
            }

            .radio-pill .pill-check::after {
                content: '';
                width: 6px; height: 6px;
                border-radius: 50%;
                background: #fff;
                display: none;
            }

            .radio-pill:has(input:checked) .pill-check::after { display: block; }

            /* ── Service row ── */
            .service-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: .875rem 0;
                border-bottom: 1px solid #F3F4F6;
            }

            .service-row:last-child { border-bottom: none; }

            .service-name {
                font-weight: 600;
                font-size: .875rem;
                color: #111827;
            }

            .service-price {
                font-size: .75rem;
                color: #6B7280;
                margin-top: .15rem;
            }

            .qty-input {
                width: 90px;
                border: 1.5px solid #E5E7EB;
                border-radius: 9px;
                padding: .5rem .75rem;
                font-size: .875rem;
                font-family: inherit;
                text-align: center;
                outline: none;
                transition: border-color .18s, box-shadow .18s;
                background: #FAFAFA;
            }

            .qty-input:focus {
                border-color: var(--brand);
                box-shadow: 0 0 0 3px rgba(108,99,255,.12);
                background: #fff;
            }

            .qty-unit {
                font-size: .75rem;
                color: #9CA3AF;
                min-width: 32px;
            }

            /* ── Tier group ── */
            .tier-group-title {
                font-size: .75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: #6B7280;
                margin-bottom: .5rem;
                margin-top: 1rem;
            }

            .tier-group-title:first-child { margin-top: 0; }

            .tier-block {
                border: 1px solid #E5E7EB;
                border-radius: 12px;
                overflow: hidden;
            }

            /* ── Buttons ── */
            .btn-primary {
                display: inline-flex;
                align-items: center;
                gap: .5rem;
                background: linear-gradient(135deg, #6C63FF, #4F46E5);
                color: #fff;
                border: none;
                border-radius: 10px;
                padding: .7rem 1.5rem;
                font-size: .875rem;
                font-weight: 700;
                font-family: inherit;
                cursor: pointer;
                transition: all .2s;
                text-decoration: none;
                box-shadow: 0 4px 14px rgba(108,99,255,.35);
            }

            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(108,99,255,.4);
            }

            .btn-primary:active { transform: translateY(0); }

            .btn-ghost {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                background: transparent;
                color: #6C63FF;
                border: 1.5px solid #C7D2FE;
                border-radius: 10px;
                padding: .65rem 1.25rem;
                font-size: .85rem;
                font-weight: 600;
                font-family: inherit;
                cursor: pointer;
                transition: all .2s;
                text-decoration: none;
            }

            .btn-ghost:hover {
                background: var(--brand-light);
                border-color: var(--brand);
            }

            /* ── Badge / status ── */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: .25rem .625rem;
                border-radius: 999px;
                font-size: .73rem;
                font-weight: 700;
                letter-spacing: .01em;
            }

            .badge-pending { background: #FEF3C7; color: #92400E; }
            .badge-processing { background: #DBEAFE; color: #1E40AF; }
            .badge-ready { background: #D1FAE5; color: #065F46; }
            .badge-done { background: #F3F4F6; color: #374151; }
            .badge-paid { background: #D1FAE5; color: #065F46; }
            .badge-unpaid { background: #FEE2E2; color: #991B1B; }
            .badge-online { background: #EDE9FE; color: #5B21B6; }
            .badge-self { background: #F0FDF4; color: #166534; }

            /* ── Success / tracking page ── */
            .invoice-hero {
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
                border-radius: 16px;
                padding: 1.5rem 2rem;
                color: #fff;
                margin-bottom: 1.5rem;
            }

            .invoice-code {
                font-size: 1.8rem;
                font-weight: 800;
                letter-spacing: .05em;
                margin: .25rem 0 .5rem;
            }

            /* ── Status timeline ── */
            .timeline { display: flex; flex-direction: column; gap: 0; }
            .tl-item { display: flex; gap: .875rem; }
            .tl-line { display: flex; flex-direction: column; align-items: center; }
            .tl-dot {
                width: 14px; height: 14px;
                border-radius: 50%;
                border: 2px solid #D1D5DB;
                background: #fff;
                flex-shrink: 0;
                margin-top: 3px;
            }
            .tl-dot.done { background: var(--brand); border-color: var(--brand); }
            .tl-connector {
                width: 2px;
                flex: 1;
                background: #E5E7EB;
                min-height: 28px;
            }
            .tl-label { font-size: .85rem; font-weight: 600; color: #374151; }
            .tl-sub { font-size: .75rem; color: #9CA3AF; margin-top: .1rem; padding-bottom: .875rem; }

            /* ── Track form ── */
            .track-search {
                display: flex;
                gap: .625rem;
            }

            .track-search .form-input {
                font-size: 1rem;
                padding: .7rem 1rem;
                letter-spacing: .05em;
                font-weight: 600;
            }

            /* ── Grid ── */
            .grid-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.25rem;
            }

            .grid-3 {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 1.25rem;
            }

            @media (max-width: 640px) {
                .grid-2, .grid-3 { grid-template-columns: 1fr; }
                .pub-logo-name { font-size: .95rem; }
                .page-hero { padding: 1.25rem; }
                .page-hero h1 { font-size: 1.15rem; }
                .track-search { flex-direction: column; }
            }

            /* ── Footer ── */
            .pub-footer {
                border-top: 1px solid #E5E7EB;
                background: #fff;
                padding: 1.25rem 1.5rem;
                text-align: center;
                font-size: .78rem;
                color: #9CA3AF;
            }

            /* ── Animations ── */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(12px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .pub-main > * { animation: fadeUp .35s ease both; }
            .pub-main > *:nth-child(2) { animation-delay: .05s; }
            .pub-main > *:nth-child(3) { animation-delay: .10s; }
            .pub-main > *:nth-child(4) { animation-delay: .15s; }
            .pub-main > *:nth-child(5) { animation-delay: .20s; }
        </style>
    </head>
    <body>
        <header class="pub-header">
            <div class="pub-header-inner">
                <a href="{{ route('public.order.create') }}" class="pub-logo">
                    <div class="pub-logo-icon">🧺</div>
                    <span class="pub-logo-name">{{ config('app.name', 'Laundry') }}</span>
                </a>

                <nav class="pub-tab-nav">
                    <a href="{{ route('public.order.create') }}"
                       class="pub-tab {{ request()->routeIs('public.order.*') ? 'active' : '' }}">
                        <span class="tab-dot"></span>
                        📋 New Order
                    </a>
                    <a href="{{ route('public.track.form') }}"
                       class="pub-tab {{ request()->routeIs('public.track.*') ? 'active' : '' }}">
                        <span class="tab-dot"></span>
                        🔍 Track Order
                    </a>
                </nav>
            </div>
        </header>

        <main class="pub-main">
            @if ($errors->any())
                <div class="err-alert">
                    <div class="err-alert-title">⚠️ Please fix these issues:</div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="pub-footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laundry') }} — Powered by Laravel + Filament
        </footer>
    </body>
</html>
