<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Absensi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/detik-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body text-gray-900 antialiased bg-gray-50">

    <!-- Topbar -->
    <header class="bg-brand-navy">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/detik-icon.png') }}" alt="" width="36" height="36" class="w-9 h-9 shrink-0">
                <span class="font-heading text-white font-semibold text-lg">Absensi Magang</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="font-body text-white/80 text-sm hidden sm:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-white/30 px-3 py-1.5 text-sm font-body text-white hover:bg-white/10 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z" clip-rule="evenodd" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        @if (session('success'))
            <div class="rounded-lg bg-emerald-50 text-emerald-700 text-sm font-body px-4 py-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-lg bg-red-50 text-red-700 text-sm font-body px-4 py-3">{{ session('error') }}</div>
        @endif

        <!-- Hero card -->
        <div class="rounded-2xl bg-gradient-to-br from-brand-navy to-brand-blue p-6 sm:p-8 text-white shadow-lg">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="font-heading text-xl sm:text-2xl font-semibold">Absensi hari ini</h1>
                    <p class="font-body text-white/70 text-sm mt-1">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p id="greeting" class="font-body text-white/70 text-sm"></p>
                    <p id="live-clock" class="font-heading text-xl sm:text-2xl font-semibold tabular-nums mt-1">--:--:--</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-6 max-w-xs">
                <div>
                    <p class="font-body text-xs tracking-wide text-white/60">Check-in</p>
                    <p class="font-body text-[11px] text-white/40">sebelum {{ config('absensi.batas_checkin') }}</p>
                    <p class="font-heading text-2xl font-semibold mt-1">{{ $today->check_in ?? '--:--' }}</p>
                </div>
                <div>
                    <p class="font-body text-xs tracking-wide text-white/60">Check-out</p>
                    <p class="font-body text-[11px] text-white/40">mulai {{ config('absensi.mulai_checkout') }}</p>
                    <p class="font-heading text-2xl font-semibold mt-1">{{ $today->check_out ?? '--:--' }}</p>
                </div>
            </div>

            @if ($today && $today->check_out)
                <div class="mt-6 inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2.5 font-body font-medium text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-emerald-400" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                    Absensi hari ini sudah selesai
                </div>
            @else
                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('attendance.check-in') }}">
                        @csrf
                        <button type="submit" {{ $today ? 'disabled' : '' }}
                            class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 font-body font-semibold transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white
                                {{ $today
                                    ? 'bg-white/10 text-white/40 cursor-not-allowed'
                                    : 'bg-brand-orange text-white hover:bg-brand-orange/90' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                            </svg>
                            Check In
                        </button>
                    </form>
                    <form method="POST" action="{{ route('attendance.check-out') }}">
                        @csrf
                        <button type="submit" {{ (! $today || $today->check_out) ? 'disabled' : '' }}
                            class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 font-body font-semibold transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white
                                {{ (! $today || $today->check_out)
                                    ? 'bg-white/10 text-white/40 cursor-not-allowed'
                                    : 'bg-brand-orange text-white hover:bg-brand-orange/90' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                            </svg>
                            Check Out
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Stat cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="font-body text-sm text-gray-500">Total Kehadiran Bulan Ini</p>
                <p class="font-heading text-2xl font-semibold text-brand-navy mt-1">{{ $totalHadir }} hari</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="font-body text-sm text-gray-500">Rata-rata Jam Check-in</p>
                <p class="font-heading text-2xl font-semibold text-brand-navy mt-1">{{ $avgCheckIn }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="font-body text-sm text-gray-500">Hari Terlambat Bulan Ini</p>
                <p class="font-heading text-2xl font-semibold text-brand-navy mt-1">{{ $totalTelat }} hari</p>
            </div>
        </div>

        <!-- Riwayat -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-heading text-lg font-semibold text-brand-navy">Riwayat Absensi</h2>
            </div>

            @php
                $dotClass = fn ($status) => match ($status) {
                    'hadir' => 'bg-emerald-500',
                    'telat' => 'bg-amber-500',
                    'alpha' => 'bg-brand-orange',
                };
            @endphp

            <!-- Desktop table -->
            <table class="hidden sm:table w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr class="text-left">
                        <th scope="col" class="px-6 py-3 font-body font-medium">Tanggal</th>
                        <th scope="col" class="px-6 py-3 font-body font-medium">Check In</th>
                        <th scope="col" class="px-6 py-3 font-body font-medium">Check Out</th>
                        <th scope="col" class="px-6 py-3 font-body font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($history as $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-body text-gray-700">{{ $row->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-3 font-body text-gray-700">{{ $row->check_in ?? '-' }}</td>
                            <td class="px-6 py-3 font-body text-gray-700">{{ $row->check_out ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center gap-2 font-body text-gray-700">
                                    <span class="h-2 w-2 rounded-full {{ $dotClass($row->status) }}"></span>
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 font-body">Belum ada riwayat absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile list -->
            <div class="sm:hidden divide-y divide-gray-100">
                @forelse ($history as $row)
                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="font-body font-medium text-gray-800">{{ $row->date->format('d/m/Y') }}</span>
                            <span class="inline-flex items-center gap-1.5 font-body text-sm text-gray-600">
                                <span class="h-2 w-2 rounded-full {{ $dotClass($row->status) }}"></span>
                                {{ ucfirst($row->status) }}
                            </span>
                        </div>
                        <p class="mt-1 font-body text-sm text-gray-500">
                            Masuk {{ $row->check_in ?? '-' }} &middot; Keluar {{ $row->check_out ?? '-' }}
                        </p>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-gray-400 font-body text-sm">Belum ada riwayat absensi.</div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function updateLiveClock() {
            const now = new Date();
            const pad = (n) => String(n).padStart(2, '0');

            document.getElementById('live-clock').textContent =
                `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

            const hour = now.getHours();
            let greeting;
            if (hour >= 4 && hour < 11) greeting = 'Selamat pagi';
            else if (hour >= 11 && hour < 15) greeting = 'Selamat siang';
            else if (hour >= 15 && hour < 18) greeting = 'Selamat sore';
            else greeting = 'Selamat malam';
            document.getElementById('greeting').textContent = greeting;
        }

        updateLiveClock();
        setInterval(updateLiveClock, 1000);
    </script>
</body>
</html>
