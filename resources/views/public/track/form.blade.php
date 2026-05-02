<x-layouts.public :title="'Track Your Order'">

    <div class="page-hero">
        <h1>🔍 Track Your Order</h1>
        <p>Enter your invoice code to check the real-time status of your laundry.</p>
    </div>

    <div class="card" style="max-width:36rem;">
        <div class="card-title"><span class="icon">🧾</span> Invoice Lookup</div>
        <form method="GET" action="{{ route('public.track.form') }}">
            <div class="track-search">
                <input
                    name="invoice"
                    value="{{ request('invoice') }}"
                    class="form-input"
                    placeholder="INV-XXXXXX"
                    style="letter-spacing:.06em;font-weight:700;text-transform:uppercase;"
                    oninput="this.value = this.value.toUpperCase()"
                />
                <button type="submit" class="btn-primary" style="white-space:nowrap;">
                    🔍 Track
                </button>
            </div>
            <p style="font-size:.78rem;color:#9CA3AF;margin:.625rem 0 0;">
                Example: <code style="background:#F3F4F6;padding:.1rem .35rem;border-radius:4px;">INV-ABC123</code>
            </p>
        </form>

        @if (request('invoice'))
            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #F3F4F6;">
                <a class="btn-ghost" href="{{ route('public.track.show', ['invoiceCode' => request('invoice')]) }}">
                    Open tracking page for <strong>{{ request('invoice') }}</strong> →
                </a>
            </div>
        @endif
    </div>

    <div style="margin-top:1.25rem;">
        <a class="btn-ghost" href="{{ route('public.order.create') }}">
            ← Back to New Order
        </a>
    </div>

</x-layouts.public>
