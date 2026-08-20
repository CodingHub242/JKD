<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title ?? 'Admin' }} · {{ $site['company_name'] ?? 'JKD PINNacle' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebar: false }" class="bg-ink-950 text-ink-100 font-sans antialiased">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside style="background: linear-gradient(to bottom, #0c142675 10%, #0c142657 100%), url('../CEO.jpeg') no-repeat center center / cover !important;"
            class="fixed inset-y-0 left-0 z-50 w-64 transform border-r border-white/10 bg-ink-900 p-4 transition-transform lg:static lg:translate-x-0"
            :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center gap-2 px-2 py-3 font-display text-lg font-bold text-white">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-500 text-ink-950">J</span>
                <span>{{ $site['company_name'] ?? 'JKD PINNACLE CONSTRUCTION' }}</span>
            </div>

            <nav class="mt-4 space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="admin-link">Dashboard</a>

                <p class="px-3 pt-4 text-xs uppercase tracking-wider text-ink-500">Content</p>
                <a href="{{ route('admin.content.index', 'sliders') }}" class="admin-link">Hero Sliders</a>
                <a href="{{ route('admin.content.index', 'services') }}" class="admin-link">Services</a>
                <a href="{{ route('admin.content.index', 'projects') }}" class="admin-link">Projects</a>
                <a href="{{ route('admin.content.index', 'team') }}" class="admin-link">Team</a>
                <a href="{{ route('admin.content.index', 'testimonials') }}" class="admin-link">Testimonials</a>

                <p class="px-3 pt-4 text-xs uppercase tracking-wider text-ink-500">Submissions</p>
                <a href="{{ route('admin.submissions.index', 'quotes') }}" class="admin-link">Quotes</a>
                <a href="{{ route('admin.submissions.index', 'contacts') }}" class="admin-link">Messages</a>
                <a href="{{ route('admin.submissions.index', 'site_visits') }}" class="admin-link">Site Visits</a>
                <a href="{{ route('admin.submissions.index', 'meetings') }}" class="admin-link">Meetings</a>
                <a href="{{ route('admin.submissions.index', 'applications') }}" class="admin-link">Applications</a>

                <p class="px-3 pt-4 text-xs uppercase tracking-wider text-ink-500">Tools</p>
                <a href="{{ route('admin.chat.index') }}" class="admin-link">Live Chat</a>
                <a href="{{ route('admin.settings') }}" class="admin-link">Settings</a>
            </nav>

            <div class="absolute bottom-4 left-4 right-4 space-y-2">
                <a href="{{ url('/') }}" target="_blank" class="admin-link">View Site &rarr;</a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-link w-full text-left">Log Out</button>
                </form>
            </div>
        </aside>

        @if(session('success'))
            <div class="fixed top-4 right-4 z-[70] rounded-xl border border-brand-400/40 bg-brand-500/15 px-4 py-3 text-sm text-brand-200 shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex-1">
            <header class="flex items-center justify-between border-b border-white/10 bg-ink-900/60 px-5 py-4 lg:px-8">
                <button class="lg:hidden text-white" @click="sidebar = true" aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="font-display text-lg font-semibold text-white">{{ $title ?? 'Admin' }}</h1>
                <span class="text-sm text-ink-400">{{ Auth::user()->name ?? '' }}</span>
            </header>

            <main class="p-5 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
