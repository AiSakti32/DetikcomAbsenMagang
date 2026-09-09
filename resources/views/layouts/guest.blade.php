<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk &mdash; Absensi Magang detikcom</title>
    <link rel="icon" type="image/png" href="{{ asset('images/detik-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .underline-input {
            border: none;
            border-bottom: 1.5px solid #CBD5E1;
            border-radius: 0;
            background: transparent;
            padding: 0.75rem 0.1rem;
            width: 100%;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
            outline: none;
            color: #0F172A;
        }
        .underline-input:focus {
            border-bottom-color: #0857C3;
            box-shadow: 0 1px 0 0 #0857C3;
        }
        .underline-input::placeholder {
            color: #94A3B8;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="min-h-screen bg-white flex flex-col md:flex-row antialiased font-body selection:bg-brand-blue selection:text-white">

    <!-- LEFT PANEL / TOP BANNER ON MOBILE -->
    <aside class="relative w-full md:w-[60%] shrink-0 overflow-hidden flex flex-col justify-between text-white z-0 min-h-[340px] md:min-h-screen">

        <!-- Background Office Image -->
        <div class="absolute inset-0 -z-20">
            <img src="{{ asset('images/detik-wall.jpeg') }}"
                alt=""
                class="w-full h-full object-cover object-center transform md:scale-105 transition-transform duration-1000">
        </div>

        <!-- Gradient overlay -->
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-brand-navy/95 via-brand-blue/85 to-brand-orange/75 mix-blend-multiply opacity-95"></div>
        <div class="absolute inset-0 -z-10 bg-brand-navy/40 backdrop-blur-[1px]"></div>

        <!-- Top: Brand mark -->
        <div class="p-6 md:p-12 lg:p-16 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl overflow-hidden shadow-md ring-1 ring-white/20 bg-brand-navy p-1 flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/detik-icon.png') }}" alt="" class="w-full h-full object-cover rounded-lg">
                </div>
                <div class="flex flex-col">
                    <span class="font-heading font-bold text-lg tracking-tight leading-none text-white">Absensi Magang</span>
                    <span class="text-[10px] tracking-wider uppercase font-medium text-blue-200/90 mt-1">detikcom Intern Program</span>
                </div>
            </div>

            <div class="hidden sm:inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-medium text-white/90">
                <span class="w-2 h-2 rounded-full bg-brand-orange animate-pulse"></span>
                Batch 2025/2026
            </div>
        </div>

        <!-- Center: editorial statement -->
        <div class="px-6 py-4 md:px-12 lg:px-16 my-auto max-w-xl">
            <h1 class="font-heading font-bold text-2xl sm:text-3xl md:text-4xl lg:text-[42px] leading-[1.18] tracking-tight text-white mb-4">
                Satu platform untuk seluruh kehadiran magang.
            </h1>
            <p class="text-sm sm:text-base text-slate-200/90 font-normal leading-relaxed max-w-md">
                Peserta check-in dan check-out dalam sekali klik, admin memantau kehadiran dan laporan secara real-time semua dalam satu sistem terpusat.
            </p>
        </div>

        <!-- Bottom: stats -->
        <div class="p-6 md:p-12 lg:p-16 border-t border-white/15 bg-black/10 backdrop-blur-sm">
            <div class="grid grid-cols-3 gap-4 sm:gap-6">
                <div class="flex flex-col">
                    <span class="font-heading font-bold text-xl sm:text-2xl md:text-3xl text-white tracking-tight">{{ config('absensi.batas_checkin') }}</span>
                    <span class="text-[11px] sm:text-xs text-slate-300 font-medium mt-0.5 leading-snug">Batas check-in</span>
                </div>

                <div class="flex flex-col border-l border-white/15 pl-4 sm:pl-6">
                    <span class="font-heading font-bold text-xl sm:text-2xl md:text-3xl text-white tracking-tight">2</span>
                    <span class="text-[11px] sm:text-xs text-slate-300 font-medium mt-0.5 leading-snug">Peran pengguna</span>
                </div>

                <div class="flex flex-col border-l border-white/15 pl-4 sm:pl-6">
                    <div class="flex items-baseline gap-1">
                        <span class="font-heading font-bold text-xl sm:text-2xl md:text-3xl text-white tracking-tight">1</span>
                        <span class="font-heading font-semibold text-xs sm:text-sm text-orange-300">klik</span>
                    </div>
                    <span class="text-[11px] sm:text-xs text-slate-300 font-medium mt-0.5 leading-snug">Untuk absen</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- RIGHT PANEL: form -->
    <main class="w-full md:w-[40%] flex items-center justify-center p-6 sm:p-10 md:p-12 lg:p-16 bg-white shrink-0">
        <div class="w-full max-w-[360px] mx-auto flex flex-col justify-center">

            {{ $slot }}
        </div>
    </main>

</body>
</html>
