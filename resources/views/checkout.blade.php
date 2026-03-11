@extends('layouts.app')

@section('title', 'Checkout — ' . $siteName)

@section('content')
<div class="container">
    <div class="checkout-page">

        <div style="margin-bottom:var(--sp-6)">
            <h1 style="font-size:var(--text-3xl);font-weight:var(--weight-bold);letter-spacing:var(--tracking-tight)">
                Checkout</h1>
            <p style="color:var(--text-muted);font-size:var(--text-sm);margin-top:var(--sp-1)">
                <a href="{{ route('cart.index') }}"
                    style="color:var(--blue);display:inline-flex;align-items:center;gap:4px">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Edit cart
                </a>
            </p>
        </div>

        <form action="{{ route('checkout.submit') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="checkout-grid">

                {{-- ── Left: form sections ─────────────────────── --}}
                <div>

                    {{-- Step 1: Contact + Shipping --}}
                    <div class="checkout-section">
                        <div class="checkout-section-header">
                            <span class="checkout-step-num">1</span>
                            <span class="checkout-section-title">Contact & Shipping</span>
                        </div>
                        <div class="checkout-section-body">

                            <div class="form-row-2" style="margin-bottom:var(--sp-4)">
                                <div class="form-group">
                                    <label class="form-label" for="customer_name">Full Name <span
                                            class="req">*</span></label>
                                    <input type="text" id="customer_name" name="customer_name"
                                        class="form-control{{ $errors->has('customer_name') ? ' error' : '' }}"
                                        value="{{ old('customer_name') }}" placeholder="Your full name" required>
                                    @error('customer_name')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="customer_phone">Phone Number</label>
                                    <input type="tel" id="customer_phone" name="customer_phone"
                                        class="form-control{{ $errors->has('customer_phone') ? ' error' : '' }}"
                                        value="{{ old('customer_phone') }}" placeholder="+1 555 000 0000">
                                    @error('customer_phone')
                                    <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom:var(--sp-4)">
                                <label class="form-label" for="customer_email">Email Address <span
                                        class="req">*</span></label>
                                <input type="email" id="customer_email" name="customer_email"
                                    class="form-control{{ $errors->has('customer_email') ? ' error' : '' }}"
                                    value="{{ old('customer_email') }}" placeholder="you@example.com" required>
                                <span class="form-hint">Your order confirmation will be sent here.</span>
                                @error('customer_email')
                                <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom:var(--sp-4)">
                                <label class="form-label" for="shipping_address">Delivery Address <span
                                        class="req">*</span></label>
                                <input type="text" id="shipping_address" name="shipping_address"
                                    class="form-control{{ $errors->has('shipping_address') ? ' error' : '' }}"
                                    value="{{ old('shipping_address') }}" placeholder="Street address, apartment, etc."
                                    required>
                                @error('shipping_address')
                                <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="shipping_city">City <span class="req">*</span></label>
                                <input type="text" id="shipping_city" name="shipping_city"
                                    class="form-control{{ $errors->has('shipping_city') ? ' error' : '' }}"
                                    value="{{ old('shipping_city') }}" placeholder="City" required>
                                @error('shipping_city')
                                <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Step 2: Notes --}}
                    <div class="checkout-section">
                        <div class="checkout-section-header">
                            <span class="checkout-step-num">2</span>
                            <span class="checkout-section-title">Order Notes <span
                                    style="font-weight:normal;color:var(--text-muted)">(optional)</span></span>
                        </div>
                        <div class="checkout-section-body">
                            <div class="form-group">
                                <label class="form-label" for="notes">Special instructions for your order</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3"
                                    placeholder="e.g. Leave at door, call before delivery…">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Payment method info (COD only) --}}
                    <div class="checkout-section">
                        <div class="checkout-section-header">
                            <span class="checkout-step-num">3</span>
                            <span class="checkout-section-title">Payment</span>
                        </div>
                        <div class="checkout-section-body">
                            <div
                                style="display:flex;align-items:center;gap:var(--sp-3);padding:var(--sp-4);background:var(--gray-50);border-radius:var(--radius-lg);border:1.5px solid var(--success-border)">
                                <div
                                    style="width:40px;height:40px;background:var(--success-bg);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--success)"
                                        stroke-width="2">
                                        <rect x="2" y="5" width="20" height="14" rx="2" />
                                        <line x1="2" y1="10" x2="22" y2="10" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-weight:var(--weight-semibold);font-size:var(--text-sm)">Cash on
                                        Delivery</div>
                                    <div style="font-size:var(--text-xs);color:var(--text-muted)">Pay when your order
                                        arrives. No card required.</div>
                                </div>
                                <div style="margin-left:auto">
                                    <div
                                        style="width:20px;height:20px;background:var(--success);border-radius:var(--radius-full);display:flex;align-items:center;justify-content:center">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white"
                                            stroke-width="3">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:var(--sp-2)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Place Order
                    </button>
                    <p
                        style="font-size:var(--text-xs);color:var(--text-muted);text-align:center;margin-top:var(--sp-3)">
                        By placing your order you agree to our terms of service.
                    </p>
                </div>

                {{-- ── Right: order summary ─────────────────────── --}}
                <div>
                    <div class="checkout-summary">
                        <div class="checkout-summary-header">
                            <div class="checkout-summary-title">Order Summary ({{ count($cart) }} items)</div>
                        </div>
                        <div class="checkout-items">
                            @foreach($cart as $item)
                            <div class="checkout-item">
                                <div class="checkout-item-img">
                                    @if($item['image'])
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}">
                                    @else
                                    <img src="{{ asset('images/placeholder-product.png') }}" alt="{{ $item['name'] }}">
                                    @endif
                                    <span class="checkout-item-qty">{{ $item['quantity'] }}</span>
                                </div>
                                <div class="checkout-item-info">
                                    <div class="checkout-item-name">{{ $item['name'] }}</div>
                                    @if(!empty($item['variant']))
                                    <div class="checkout-item-variant">
                                        @foreach($item['variant'] as $k => $v)
                                        {{ ucfirst($k) }}: {{ $v }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="checkout-item-price">
                                    {{ $currencySymbol }}{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="checkout-summary-totals">
                            <div class="summary-line">
                                <span>Subtotal</span>
                                <span>{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="summary-line">
                                <span>Shipping</span>
                                <span>
                                    @if($shipping == 0)
                                    <span style="color:var(--success);font-weight:600">Free</span>
                                    @else
                                    {{ $currencySymbol }}{{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="summary-line total">
                                <span>Total</span>
                                <span style="color:var(--navy)">{{ $currencySymbol }}{{ number_format($total, 2)
                                    }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /checkout-grid --}}
        </form>

    </div>
</div>
@endsection