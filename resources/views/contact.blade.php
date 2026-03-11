@extends('layouts.app')

@section('title', 'Contact — ' . $siteName)
@section('meta_description', 'Get in touch with ' . ($siteName ?? 'Vaulted') . '. We respond within 24 hours.')

@section('content')
<div class="container">
    <div class="contact-page-wrap">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="breadcrumb-sep">›</span>
            <span class="breadcrumb-current">Contact</span>
        </nav>

        <div class="contact-grid">

            {{-- Left: form --}}
            <div>
                <div style="margin-bottom:var(--sp-8)">
                    <div class="label-overline" style="margin-bottom:var(--sp-2)">Get in touch</div>
                    <h1 class="display-md" style="margin-bottom:var(--sp-3)">We'd love to hear from you.</h1>
                    <p style="color:var(--text-secondary);font-size:var(--text-base)">
                        Questions, feedback, or just want to say hello — we respond within 24 hours.
                    </p>
                </div>

                @include('partials.flash')

                <form action="{{ route('contact.submit') }}" method="POST"
                    style="display:flex;flex-direction:column;gap:var(--sp-5)">
                    @csrf

                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name <span class="req">*</span></label>
                            <input type="text" id="name" name="name"
                                class="form-control{{ $errors->has('name') ? ' error' : '' }}" value="{{ old('name') }}"
                                placeholder="Your name" required>
                            @error('name')
                            <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}"
                                placeholder="+1 555 000 0000">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address <span class="req">*</span></label>
                        <input type="email" id="email" name="email"
                            class="form-control{{ $errors->has('email') ? ' error' : '' }}" value="{{ old('email') }}"
                            placeholder="you@example.com" required>
                        @error('email')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') }}"
                            placeholder="What's this about?">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="message">Message <span class="req">*</span></label>
                        <textarea id="message" name="message" rows="5"
                            class="form-control{{ $errors->has('message') ? ' error' : '' }}"
                            placeholder="Tell us how we can help…" required>{{ old('message') }}</textarea>
                        @error('message')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            Send Message
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>

            {{-- Right: contact info --}}
            <div>
                <div
                    style="background:var(--navy);border-radius:var(--radius-xl);padding:var(--sp-8);color:rgba(255,255,255,0.85)">
                    <h2
                        style="font-size:var(--text-xl);font-weight:var(--weight-bold);color:var(--white);margin-bottom:var(--sp-6)">
                        Contact Info
                    </h2>

                    <div style="display:flex;flex-direction:column;gap:var(--sp-5)">

                        @if(!empty($settings['site_phone']))
                        <div style="display:flex;gap:var(--sp-4);align-items:flex-start">
                            <div
                                style="width:40px;height:40px;background:rgba(37,99,235,0.2);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--blue)"
                                    stroke-width="2">
                                    <path
                                        d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8a19.79 19.79 0 01-3.07-8.63A2 2 0 012 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 9.91a16 16 0 006.29 6.29l1.28-1.28a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" />
                                </svg>
                            </div>
                            <div>
                                <div
                                    style="font-size:var(--text-xs);text-transform:uppercase;letter-spacing:var(--tracking-wider);font-weight:var(--weight-semibold);color:rgba(255,255,255,0.4);margin-bottom:4px">
                                    Phone</div>
                                <a href="tel:{{ $settings['site_phone'] }}"
                                    style="color:var(--white);font-weight:var(--weight-medium)">
                                    {{ $settings['site_phone'] }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(!empty($settings['site_email']))
                        <div style="display:flex;gap:var(--sp-4);align-items:flex-start">
                            <div
                                style="width:40px;height:40px;background:rgba(37,99,235,0.2);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--blue)"
                                    stroke-width="2">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </div>
                            <div>
                                <div
                                    style="font-size:var(--text-xs);text-transform:uppercase;letter-spacing:var(--tracking-wider);font-weight:var(--weight-semibold);color:rgba(255,255,255,0.4);margin-bottom:4px">
                                    Email</div>
                                <a href="mailto:{{ $settings['site_email'] }}"
                                    style="color:var(--white);font-weight:var(--weight-medium)">
                                    {{ $settings['site_email'] }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(!empty($settings['site_address']))
                        <div style="display:flex;gap:var(--sp-4);align-items:flex-start">
                            <div
                                style="width:40px;height:40px;background:rgba(37,99,235,0.2);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--blue)"
                                    stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </div>
                            <div>
                                <div
                                    style="font-size:var(--text-xs);text-transform:uppercase;letter-spacing:var(--tracking-wider);font-weight:var(--weight-semibold);color:rgba(255,255,255,0.4);margin-bottom:4px">
                                    Address</div>
                                <div
                                    style="color:var(--white);font-weight:var(--weight-medium);line-height:var(--leading-relaxed)">
                                    {{ $settings['site_address'] }}
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>

                    <div
                        style="margin-top:var(--sp-8);padding-top:var(--sp-6);border-top:1px solid rgba(255,255,255,0.1)">
                        <div
                            style="font-size:var(--text-xs);color:rgba(255,255,255,0.4);margin-bottom:var(--sp-3);text-transform:uppercase;letter-spacing:var(--tracking-wider);font-weight:var(--weight-semibold)">
                            Follow Us</div>
                        <div style="display:flex;gap:var(--sp-2)">
                            @if(!empty($socialLinks['instagram']))
                            <a href="{{ $socialLinks['instagram'] }}" target="_blank"
                                style="width:36px;height:36px;background:rgba(255,255,255,0.08);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);transition:all 0.2s">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="2" y="2" width="20" height="20" rx="5" />
                                    <circle cx="12" cy="12" r="4" />
                                    <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" />
                                </svg>
                            </a>
                            @endif
                            @if(!empty($socialLinks['facebook']))
                            <a href="{{ $socialLinks['facebook'] }}" target="_blank"
                                style="width:36px;height:36px;background:rgba(255,255,255,0.08);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Response time badge --}}
                <div
                    style="margin-top:var(--sp-4);background:var(--success-bg);border:1px solid var(--success-border);border-radius:var(--radius-lg);padding:var(--sp-4);display:flex;align-items:center;gap:var(--sp-3)">
                    <div
                        style="width:10px;height:10px;background:var(--success);border-radius:var(--radius-full);flex-shrink:0;box-shadow:0 0 0 3px rgba(22,163,74,0.2)">
                    </div>
                    <div>
                        <div style="font-size:var(--text-sm);font-weight:var(--weight-semibold);color:var(--success)">
                            Usually replies within 24 hours</div>
                        <div style="font-size:var(--text-xs);color:var(--text-muted)">Mon – Fri, 9am – 6pm</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection