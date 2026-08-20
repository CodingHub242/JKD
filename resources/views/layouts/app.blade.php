<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription ?? 'JKD PINNacle — world-class construction, design and build solutions.' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'JKD PINNACLE CONSTRUCTION' }} · Building Excellence</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ mobileOpen: false }" :class="mobileOpen && 'overflow-hidden'">

    {{-- Preloader --}}
    <div id="preloader" aria-hidden="true">
        <div class="preloader__mark">{{ $site['loading_text'] ?? 'Welcome to JKD PINNacle' }}</div>
        <div class="preloader__sub">{{ $site['loading_subtext'] ?? 'Building Excellence' }}</div>
        <div class="preloader__bar"><span></span></div>
    </div>

    {{-- Navigation --}}
    <header
        x-data="{ scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 40"
        :class="scrolled && 'site-nav--scrolled'"
        class="site-nav"
    >
        <nav class="container-x flex h-20 items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 font-display text-lg font-bold tracking-tight text-white">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-500 text-ink-950">J</span>
                <span>{{ $site['company_name'] ?? 'JKD PINNacle' }}</span>
            </a>

            <div class="hidden items-center gap-8 lg:flex">
                <a href="{{ url('/') }}" class="site-nav__link">Home</a>
                <a href="{{ url('/services') }}" class="site-nav__link">Services</a>
                <a href="{{ url('/projects') }}" class="site-nav__link">Projects</a>
                <a href="{{ url('/about') }}" class="site-nav__link">About</a>
                <a href="{{ url('/careers') }}" class="site-nav__link">Careers</a>
                <a href="{{ url('/contact') }}" class="site-nav__link">Contact</a>
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                <a href="{{ url('/tracker') }}" class="btn-ghost btn">Track Project</a>
                <a href="{{ url('/quote') }}" class="btn-primary btn">Get a Quote</a>
            </div>

            <button
                type="button"
                class="grid h-10 w-10 place-items-center rounded-lg border border-white/10 text-white lg:hidden"
                @click="mobileOpen = !mobileOpen"
                aria-label="Toggle menu"
            >
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </nav>

        {{-- Mobile menu --}}
        <div
            x-show="mobileOpen"
            x-transition.opacity
            class="border-t border-white/5 bg-ink-950/95 backdrop-blur-md lg:hidden"
        >
            <div class="container-x flex flex-col gap-1 py-4">
                <a href="{{ url('/') }}" class="rounded-lg px-3 py-3 text-ink-200 hover:bg-white/5 hover:text-white">Home</a>
                <a href="{{ url('/services') }}" class="rounded-lg px-3 py-3 text-ink-200 hover:bg-white/5 hover:text-white">Services</a>
                <a href="{{ url('/projects') }}" class="rounded-lg px-3 py-3 text-ink-200 hover:bg-white/5 hover:text-white">Projects</a>
                <a href="{{ url('/about') }}" class="rounded-lg px-3 py-3 text-ink-200 hover:bg-white/5 hover:text-white">About</a>
                <a href="{{ url('/careers') }}" class="rounded-lg px-3 py-3 text-ink-200 hover:bg-white/5 hover:text-white">Careers</a>
                <a href="{{ url('/contact') }}" class="rounded-lg px-3 py-3 text-ink-200 hover:bg-white/5 hover:text-white">Contact</a>
                <div class="mt-2 flex flex-col gap-2">
                    <a href="{{ url('/tracker') }}" class="btn-outline btn">Track Project</a>
                    <a href="{{ url('/quote') }}" class="btn-primary btn">Get a Quote</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="site-footer">
        <div class="container-x grid gap-10 py-16 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-2 font-display text-lg font-bold text-white">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-500 text-ink-950">J</span>
                    <span>{{ $site['company_name'] ?? 'JKD PINNacle' }}</span>
                </a>
                <p class="mt-4 max-w-xs text-sm text-ink-300">
                    {{ $site['company_description'] ?? 'World-class construction, design and build solutions delivered with precision and pride.' }}
                </p>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Company</h4>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ url('/about') }}" class="footer-link">About Us</a></li>
                    <li><a href="{{ url('/projects') }}" class="footer-link">Projects</a></li>
                    <li><a href="{{ url('/careers') }}" class="footer-link">Careers</a></li>
                    <li><a href="{{ url('/contact') }}" class="footer-link">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Services</h4>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ url('/services') }}" class="footer-link">What We Do</a></li>
                    <li><a href="{{ url('/quote') }}" class="footer-link">Get a Quote</a></li>
                    <li><a href="{{ url('/tracker') }}" class="footer-link">Building Tracker</a></li>
                    <li><a href="{{ url('/contact') }}" class="footer-link">Book a Meeting</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Get in touch</h4>
                <ul class="mt-4 space-y-2 text-sm text-ink-300">
                    <li><a href="mailto:{{ $site['company_email'] ?? '' }}" class="footer-link">{{ $site['company_email'] ?? '' }}</a></li>
                    <li><a href="tel:{{ $site['company_phone'] ?? '' }}" class="footer-link">{{ $site['company_phone'] ?? '' }}</a></li>
                    <li>{{ $site['company_address'] ?? '' }}</li>
                </ul>
                <div class="mt-4 flex flex-wrap gap-2">
                    @if(!empty($site['social_facebook']))<a href="{{ $site['social_facebook'] }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-lg border border-white/10 text-ink-300 transition-colors hover:border-brand-400 hover:text-brand-300" aria-label="Facebook">FB</a>@endif
                    @if(!empty($site['social_instagram']))<a href="{{ $site['social_instagram'] }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-lg border border-white/10 text-ink-300 transition-colors hover:border-brand-400 hover:text-brand-300" aria-label="Instagram">IG</a>@endif
                    @if(!empty($site['social_linkedin']))<a href="{{ $site['social_linkedin'] }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-lg border border-white/10 text-ink-300 transition-colors hover:border-brand-400 hover:text-brand-300" aria-label="LinkedIn">IN</a>@endif
                    @if(!empty($site['social_twitter']))<a href="{{ $site['social_twitter'] }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-lg border border-white/10 text-ink-300 transition-colors hover:border-brand-400 hover:text-brand-300" aria-label="Twitter">X</a>@endif
                    @if(!empty($site['social_youtube']))<a href="{{ $site['social_youtube'] }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-lg border border-white/10 text-ink-300 transition-colors hover:border-brand-400 hover:text-brand-300" aria-label="YouTube">YT</a>@endif
                </div>
            </div>
        </div>
        <div class="border-t border-white/5">
            <div class="container-x flex flex-col items-center justify-between gap-2 py-6 text-xs text-ink-400 sm:flex-row">
                <p>© {{ date('Y') }} JKD PINNACLE CONSTRUCTION. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Live Chat Widget --}}
    <div x-data="liveChat()" class="fixed bottom-6 right-6 z-50">
        {{-- Chat Button --}}
        <button
            @click="open = !open"
            class="grid h-14 w-14 place-items-center rounded-full bg-brand-500 text-ink-950 shadow-lg transition-transform hover:scale-105"
            aria-label="Open chat"
        >
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-3.46-.36L3 21l1.36-4.64A9.77 9.77 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        {{-- Chat Box --}}
        <div
            x-show="open"
            x-transition
            class="absolute bottom-20 right-0 w-80 rounded-2xl border border-white/10 bg-ink-900 shadow-2xl"
            x-init="$watch('open', value => { if (value && conversationId) loadMessages(); })"
        >
            <div class="border-b border-white/10 px-4 py-3 flex items-center justify-between">
                <div>
                    <div class="font-semibold text-white">Live Chat</div>
                    <div class="text-xs text-ink-400">We typically reply within minutes</div>
                </div>
                <button type="button" @click="resetConversation()" class="text-xs text-ink-400 hover:text-white">New chat</button>
            </div>

            <div class="max-h-80 overflow-y-auto p-4" id="live-chat-messages">
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.sender_type === 'visitor' ? 'text-left' : 'text-right'">
                        <span class="inline-block max-w-[80%] rounded-2xl px-3 py-2 text-sm" :class="msg.sender_type === 'visitor' ? 'bg-white/10 text-ink-100' : 'bg-brand-500 text-ink-950'" x-text="msg.body"></span>
                    </div>
                </template>
                <p x-show="messages.length === 0" class="text-sm text-ink-400">Start a conversation with us.</p>
            </div>

            <form class="border-t border-white/10 p-3" @submit.prevent="send">
                <div class="flex gap-2">
                    <input
                        type="text"
                        x-model="message"
                        placeholder="Type a message..."
                        class="field flex-1"
                        :disabled="sending"
                        required
                    >
                    <button type="submit" class="btn-primary btn px-4" :disabled="sending">Send</button>
                </div>
            </form>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
