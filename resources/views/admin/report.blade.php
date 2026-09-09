<x-admin-layout :title="'Report Absensi'">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-bold text-2xl sm:text-3xl text-brand-navy tracking-tight">Report Absensi</h1>
            <div class="flex items-center gap-2 mt-1 flex-wrap">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-brand-green border border-emerald-200">
                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-brand-green animate-pulse"></span>
                    Tersinkronisasi
                </span>
                <span class="text-xs text-brand-muted">Menampilkan {{ $attendances->total() }} catatan kehadiran &middot; Periode {{ $periodeLabel }}</span>
            </div>
        </div>

        <a href="{{ route('admin.report.export', request()->query()) }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-green hover:bg-[#0B6C4A] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition focus:outline-none focus:ring-2 focus:ring-brand-green focus:ring-offset-2">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            <span>Export Excel</span>
        </a>
    </div>

    <!-- Stats -->
    <section aria-label="Statistik Kehadiran" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-brand-border shadow-sm border-l-4 border-l-brand-green flex flex-col justify-between">
            <div class="flex justify-between items-center text-brand-muted text-xs font-semibold uppercase tracking-wider">
                <span>Hadir</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-brand-green border border-emerald-200">{{ $summary['persenHadir'] }}%</span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-heading font-bold text-brand-navy tabular-nums">{{ $summary['hadir'] }}</span>
                <span class="text-xs text-brand-muted">peserta</span>
            </div>
            <p class="mt-2 text-xs text-brand-muted">Tercatat tepat waktu presensi</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-brand-border shadow-sm border-l-4 border-l-brand-amber flex flex-col justify-between">
            <div class="flex justify-between items-center text-brand-muted text-xs font-semibold uppercase tracking-wider">
                <span>Telat</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-brand-amber border border-amber-200">{{ $summary['persenTelat'] }}%</span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-heading font-bold text-brand-navy tabular-nums">{{ $summary['telat'] }}</span>
                <span class="text-xs text-brand-muted">peserta</span>
            </div>
            <p class="mt-2 text-xs text-brand-muted">Lewat batas jam {{ config('absensi.batas_checkin') }} WIB</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-brand-border shadow-sm border-l-4 border-l-brand-orange flex flex-col justify-between">
            <div class="flex justify-between items-center text-brand-muted text-xs font-semibold uppercase tracking-wider">
                <span>Alpha</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-brand-orange border border-rose-200">{{ $summary['persenAlpha'] }}%</span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-heading font-bold text-brand-navy tabular-nums">{{ $summary['alpha'] }}</span>
                <span class="text-xs text-brand-muted">peserta</span>
            </div>
            <p class="mt-2 text-xs text-brand-muted">Tanpa keterangan absen</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-brand-border shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center text-brand-muted text-xs font-semibold uppercase tracking-wider">
                <span>Rata-Rata Check-in</span>
                <span class="text-[11px] text-brand-blue bg-blue-50 px-2 py-0.5 rounded border border-blue-200 font-medium">WIB</span>
            </div>
            <div class="mt-3 flex items-baseline gap-1.5">
                <span class="text-3xl font-heading font-bold text-brand-navy tabular-nums">{{ $summary['avgCheckIn'] ?? '--:--' }}</span>
                @if ($summary['avgCheckInLabel'])
                    <span class="text-xs font-medium text-brand-green">{{ $summary['avgCheckInLabel'] }}</span>
                @endif
            </div>
            <p class="mt-2 text-xs text-brand-muted">Berdasarkan data hadir terverifikasi</p>
        </div>
    </section>

    <!-- Filter Panel -->
    <section class="bg-white rounded-xl border border-brand-border shadow-sm p-5">
        <form method="GET" action="{{ route('admin.report') }}" id="report-filter-form" class="space-y-4">
            @if ($periode)
                <input type="hidden" name="periode" value="{{ $periode }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-5 space-y-1.5">
                    <label for="filter-name" class="block text-xs font-semibold text-brand-navy">Nama Peserta</label>
                    <div class="relative">
                        <input type="text" id="filter-name" name="nama" value="{{ request('nama') }}" placeholder="Cari nama..."
                            class="w-full pl-9 pr-3.5 py-2.5 bg-brand-bg border border-brand-border rounded-lg text-sm text-brand-navy placeholder-brand-muted/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-brand-blue transition">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-brand-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3 space-y-1.5">
                    <label for="filter-date" class="block text-xs font-semibold text-brand-navy">Tanggal</label>
                    <input type="date" id="filter-date" name="tanggal" value="{{ request('tanggal') }}"
                        class="w-full px-3.5 py-2.5 bg-brand-bg border border-brand-border rounded-lg text-sm text-brand-navy focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-brand-blue transition">
                </div>

                <div class="md:col-span-2 space-y-1.5">
                    <label for="filter-status" class="block text-xs font-semibold text-brand-navy">Status</label>
                    <select id="filter-status" name="status"
                        class="w-full py-2.5 pl-3.5 pr-8 bg-brand-bg border border-brand-border rounded-lg text-sm text-brand-navy focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-brand-blue transition">
                        <option value="">Semua</option>
                        <option value="hadir" @selected(request('status') === 'hadir')>Hadir</option>
                        <option value="telat" @selected(request('status') === 'telat')>Telat</option>
                        <option value="alpha" @selected(request('status') === 'alpha')>Alpha</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <a href="{{ route('admin.report') }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-4 text-sm font-semibold text-brand-muted bg-white border border-brand-border hover:bg-brand-bg hover:text-brand-navy rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </div>

            <div class="pt-3 border-t border-brand-border flex flex-wrap items-center gap-2 text-xs">
                <span class="text-brand-muted font-medium">Filter cepat:</span>
                @php
                    $pillParams = array_filter(['nama' => request('nama'), 'status' => request('status')]);
                    $pillClass = fn ($active) => $active
                        ? 'px-3 py-1 rounded-full bg-brand-navy text-white font-medium shadow-sm'
                        : 'px-3 py-1 rounded-full border border-brand-border text-brand-muted hover:bg-brand-bg transition';
                @endphp
                <a href="{{ route('admin.report', $pillParams + ['periode' => 'hari_ini']) }}" class="{{ $pillClass($periode === 'hari_ini') }}">Hari ini</a>
                <a href="{{ route('admin.report', $pillParams + ['periode' => 'minggu_ini']) }}" class="{{ $pillClass($periode === 'minggu_ini') }}">Minggu ini</a>
                <a href="{{ route('admin.report', $pillParams + ['periode' => 'bulan_ini']) }}" class="{{ $pillClass($periode === 'bulan_ini') }}">Bulan ini</a>
                <a href="{{ route('admin.report', $pillParams + ['periode' => 'semua']) }}" class="{{ $pillClass($periode === 'semua') }}">Semua data</a>
            </div>
        </form>
    </section>

    <!-- Data Table -->
    <section class="bg-white rounded-xl border border-brand-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-brand-bg/80 border-b border-brand-border text-brand-muted uppercase tracking-wider text-[11px] font-semibold">
                        <th scope="col" class="py-3.5 px-6">Tanggal</th>
                        <th scope="col" class="py-3.5 px-6">Nama Peserta</th>
                        <th scope="col" class="py-3.5 px-6">Check In</th>
                        <th scope="col" class="py-3.5 px-6">Check Out</th>
                        <th scope="col" class="py-3.5 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border text-sm">
                    @forelse ($attendances as $row)
                        @php
                            $initials = collect(explode(' ', $row->user->name))->map(fn ($w) => mb_substr($w, 0, 1))->join('');
                            $initials = mb_strtoupper(mb_substr($initials, 0, 2));
                            $statusStyle = match ($row->status) {
                                'hadir' => ['bg-emerald-50 text-brand-green border-emerald-200', 'bg-brand-green', 'Hadir'],
                                'telat' => ['bg-amber-50 text-brand-amber border-amber-200', 'bg-brand-amber', 'Telat'],
                                'alpha' => ['bg-rose-50 text-brand-orange border-rose-200', 'bg-brand-orange', 'Alpha'],
                                default => ['bg-gray-100 text-brand-muted border-gray-200', 'bg-brand-muted', ucfirst($row->status)],
                            };
                            $lateMinutes = $row->lateMinutes();
                        @endphp
                        <tr class="hover:bg-brand-bg/70 transition-colors">
                            <td class="py-4 px-6 text-brand-muted tabular-nums">{{ $row->date->format('d/m/Y') }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr {{ $row->user->avatarGradient() }} text-white font-heading text-xs font-semibold flex items-center justify-center shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-brand-navy leading-tight">{{ $row->user->name }}</div>
                                        <div class="text-[11px] text-brand-muted">{{ $row->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 tabular-nums">
                                <div class="text-slate-700 font-medium">{{ $row->check_in ?? '-' }}</div>
                                @if ($lateMinutes !== null)
                                    <div class="text-[11px] text-brand-orange font-medium">+{{ $lateMinutes >= 60 ? intdiv($lateMinutes, 60).'j ' : '' }}{{ $lateMinutes % 60 }}m batas waktu</div>
                                @endif
                            </td>
                            <td class="py-4 px-6 tabular-nums text-slate-600">{{ $row->check_out ?? '-' }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusStyle[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusStyle[1] }}"></span>
                                    {{ $statusStyle[2] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-sm text-brand-muted">Tidak ada data absensi untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="px-6 py-4 bg-brand-bg/60 border-t border-brand-border space-y-3">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="text-brand-muted">
                    Menampilkan <span class="font-semibold text-brand-navy">{{ $attendances->count() }}</span> dari <span class="font-semibold text-brand-navy">{{ $attendances->total() }}</span> catatan kehadiran
                </div>

                @if ($mostLate)
                    <div class="flex items-center gap-2 bg-amber-50/90 text-amber-900 border border-amber-200/80 px-3.5 py-1.5 rounded-lg">
                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><strong class="font-semibold">Insight:</strong> Peserta paling sering telat pada rentang ini: <strong>{{ $mostLate->user->name }} ({{ $mostLate->telat_count }}x)</strong></span>
                    </div>
                @else
                    <div class="flex items-center gap-2 bg-emerald-50/90 text-emerald-800 border border-emerald-200/80 px-3.5 py-1.5 rounded-lg">
                        <span>Semua peserta hadir tepat waktu &#127881;</span>
                    </div>
                @endif
            </div>

            @if ($attendances->hasPages())
                <div class="pt-2 border-t border-brand-border">{{ $attendances->links() }}</div>
            @endif
        </div>
    </section>

    <script>
        (function () {
            const form = document.getElementById('report-filter-form');
            const nameInput = document.getElementById('filter-name');
            const dateInput = document.getElementById('filter-date');
            const statusSelect = document.getElementById('filter-status');

            function submitForm() {
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            }

            let debounceTimer;
            nameInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(submitForm, 450);
            });

            dateInput.addEventListener('change', submitForm);
            statusSelect.addEventListener('change', submitForm);

            // Reload resets focus — if a search is active, put the cursor
            // back at the end of the name field so typing feels continuous.
            if (nameInput.value) {
                nameInput.focus();
                const value = nameInput.value;
                nameInput.value = '';
                nameInput.value = value;
            }
        })();
    </script>

</x-admin-layout>
