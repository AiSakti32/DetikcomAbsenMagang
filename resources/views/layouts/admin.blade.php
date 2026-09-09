<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin' }} &mdash; Absensi Magang detikcom</title>
    <link rel="icon" type="image/png" href="{{ asset('images/detik-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Smooth crossfade between admin pages (Peserta <-> Report Absensi).
           No-op in browsers that don't support it yet. */
        @view-transition {
            navigation: auto;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased font-body bg-brand-bg text-brand-navy selection:bg-brand-blue selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="w-full bg-brand-navy text-white sticky top-0 z-40 border-b border-[#0f2c61] shadow-sm" style="view-transition-name: admin-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6 md:gap-8">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group focus:outline-none">
                    <img src="{{ asset('images/detik-icon.png') }}" alt="" class="w-9 h-9 rounded-lg shadow-inner shrink-0 group-hover:scale-105 transition-transform duration-150">
                    <div class="flex flex-col">
                        <span class="font-heading font-bold text-base tracking-tight text-white leading-tight">Absensi Magang</span>
                        <span class="text-[10px] text-gray-300 font-medium tracking-wide uppercase">detikcom Intern Portal</span>
                    </div>
                </a>

                <nav class="hidden md:flex items-center gap-1.5 pl-2 border-l border-white/10">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                        Peserta
                    </a>
                    <a href="{{ route('admin.report') }}"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ request()->routeIs('admin.report') ? 'bg-white/15 text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                        Report Absensi
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col items-end text-right">
                    <span class="text-xs font-semibold text-white leading-tight">{{ auth()->user()->name }}</span>
                    <span class="text-[11px] text-gray-300">detikcom Talent Ops</span>
                </div>
                <details class="relative">
                    <summary class="list-none cursor-pointer">
                        <div class="relative">
                            <div class="w-9 h-9 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-sm font-heading font-bold text-white shadow-sm hover:border-white/40 transition-colors">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 border-2 border-brand-navy rounded-full"></span>
                        </div>
                    </summary>
                    <div class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-brand-border overflow-hidden z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-brand-navy hover:bg-brand-bg transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-7">
        {{ $slot }}
    </main>

    <footer class="w-full border-t border-brand-border bg-white py-4 mt-auto" style="view-transition-name: admin-footer">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-brand-muted">
            <div>&copy; {{ now()->year }} <strong>detikcom</strong> &middot; Portal Absensi Magang &amp; Evaluasi Kehadiran</div>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-brand-blue transition-colors">Panduan Magang</a>
                <a href="#" class="hover:text-brand-blue transition-colors">Bantuan Admin</a>
                <a href="#" class="hover:text-brand-blue transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

</body>
</html>
