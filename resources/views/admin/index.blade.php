<x-admin-layout :title="'Daftar Peserta'">

    <div x-data="{
            showAddModal: @js($errors->any() && ! old('user_id')),
            showPassword: false,
            password: '',
            saving: false,
            generatePassword() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%^&*';
                const random = new Uint32Array(12);
                window.crypto.getRandomValues(random);
                this.password = Array.from(random, (n) => chars[n % chars.length]).join('');
                this.showPassword = true;
            },
            showEditModal: @js($errors->any() && old('user_id') !== null),
            editing: @js(old('user_id') ? ['id' => old('user_id'), 'nama' => old('nama'), 'email' => old('email')] : ['id' => '', 'nama' => '', 'email' => '']),
            editShowPassword: false,
            editPassword: '',
            editSaving: false,
            openEditModal(id, nama, email) {
                this.editing = { id: id, nama: nama, email: email };
                this.editPassword = '';
                this.editShowPassword = false;
                this.showEditModal = true;
            },
            generateEditPassword() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%^&*';
                const random = new Uint32Array(12);
                window.crypto.getRandomValues(random);
                this.editPassword = Array.from(random, (n) => chars[n % chars.length]).join('');
                this.editShowPassword = true;
            }
        }">

    @if (session('success'))
        <div class="rounded-lg bg-emerald-50 text-emerald-700 text-sm font-medium px-4 py-3 border border-emerald-200/60">
            {{ session('success') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-heading font-bold text-2xl sm:text-3xl text-brand-navy tracking-tight">
                Daftar peserta magang
            </h1>
            <p class="text-xs sm:text-sm text-brand-muted mt-1 flex items-center gap-2 flex-wrap">
                <span>{{ $stats['total'] }} peserta terdaftar</span>
                <span class="inline-block w-1 h-1 rounded-full bg-brand-muted/50"></span>
                <span>diperbarui hari ini, {{ now()->locale('id')->translatedFormat('d M Y') }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-full bg-emerald-50 text-brand-green font-medium border border-emerald-200/60 ml-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-green animate-pulse"></span> Sistem Live
                </span>
            </p>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('admin.report') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold text-brand-navy bg-white border border-brand-border hover:bg-gray-50 hover:border-gray-300 rounded-lg shadow-sm transition-all active:scale-[0.99]">
                <svg class="w-4 h-4 text-brand-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Report absensi
            </a>

            <button type="button" @click="showAddModal = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white bg-brand-navy hover:bg-brand-blue rounded-lg shadow-sm transition-all active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah peserta
            </button>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-xl border border-brand-border shadow-sm hover:border-gray-300 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-brand-muted">Total peserta</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-brand-blue flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="font-heading text-2xl sm:text-3xl font-bold text-brand-navy">{{ $stats['total'] }}</div>
                <span class="text-[11px] text-brand-muted font-normal mt-0.5 block">Peserta magang aktif</span>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-xl border border-brand-border shadow-sm hover:border-emerald-200 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-brand-muted">Sudah check-in hari ini</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-brand-green flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="font-heading text-2xl sm:text-3xl font-bold text-brand-green">{{ $stats['sudahCheckIn'] }}<span class="text-lg sm:text-xl text-brand-green/70 font-semibold">/{{ $stats['total'] }}</span></div>
                <span class="text-[11px] text-brand-green font-medium mt-0.5 block">{{ number_format($stats['persenCheckIn'], 1) }}% kehadiran tercatat</span>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-xl border border-brand-border shadow-sm hover:border-amber-200 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-brand-muted">Terlambat hari ini</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-brand-amber flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="font-heading text-2xl sm:text-3xl font-bold text-brand-amber">{{ $stats['terlambat'] }}</div>
                <span class="text-[11px] text-brand-amber font-medium mt-0.5 block">Lewat batas {{ config('absensi.batas_checkin') }} WIB</span>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-xl border border-brand-border shadow-sm hover:border-gray-300 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-brand-muted">Belum absen</span>
                <span class="w-7 h-7 rounded-lg bg-gray-100 text-brand-muted flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="font-heading text-2xl sm:text-3xl font-bold text-brand-muted">{{ $stats['belumAbsen'] }}</div>
                <span class="text-[11px] text-brand-muted font-normal mt-0.5 block">Belum ada catatan hari ini</span>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-3 sm:p-4 rounded-xl border border-brand-border shadow-sm">
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-brand-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input
                type="text"
                id="peserta-search"
                placeholder="Cari nama atau email peserta..."
                class="block w-full pl-10 pr-4 py-2 text-xs sm:text-sm text-brand-navy bg-brand-bg border border-brand-border rounded-lg placeholder-brand-muted/70 focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all"
            />
        </div>

        <div class="flex items-center gap-2">
            <select id="peserta-status-filter"
                class="px-3.5 py-2 text-xs font-semibold text-brand-navy bg-brand-bg hover:bg-gray-100 border border-brand-border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue">
                <option value="">Semua status</option>
                <option value="hadir">Sudah check-in</option>
                <option value="telat">Terlambat</option>
                <option value="belum">Belum absen</option>
            </select>
        </div>
    </div>

    <!-- Table / Cards -->
    <div class="bg-white rounded-xl border border-brand-border shadow-sm overflow-hidden">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-brand-border bg-brand-bg/80 text-[11px] font-semibold text-brand-muted uppercase tracking-wider">
                        <th scope="col" class="py-3.5 px-6">Peserta</th>
                        <th scope="col" class="py-3.5 px-6">Terdaftar</th>
                        <th scope="col" class="py-3.5 px-6">Status hari ini</th>
                        <th scope="col" class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="peserta-table-body" class="divide-y divide-brand-border text-sm">
                    @foreach ($peserta as $p)
                        @php
                            $todayRecord = $p->attendances->first();
                            $hasCheckedIn = $todayRecord && $todayRecord->check_in;
                            $rowStatus = $hasCheckedIn ? $todayRecord->status : 'belum';
                            $initials = collect(explode(' ', $p->name))->map(fn ($w) => mb_substr($w, 0, 1))->join('');
                            $initials = mb_strtoupper(mb_substr($initials, 0, 2));
                        @endphp
                        <tr class="hover:bg-brand-bg transition-colors group"
                            data-name="{{ mb_strtolower($p->name) }}"
                            data-email="{{ mb_strtolower($p->email) }}"
                            data-status="{{ $rowStatus }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-blue to-brand-orange flex items-center justify-center text-white font-heading font-bold text-sm shadow-sm shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-brand-navy group-hover:text-brand-blue transition-colors">{{ $p->name }}</div>
                                        <div class="text-xs text-brand-muted">{{ $p->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-xs text-brand-muted font-medium">
                                {{ $p->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6">
                                @if ($hasCheckedIn && $rowStatus === 'hadir')
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-brand-green border border-emerald-200/80">
                                        <span class="w-2 h-2 rounded-full bg-brand-green"></span>
                                        Check-in {{ substr($todayRecord->check_in, 0, 5) }}
                                    </span>
                                @elseif ($hasCheckedIn && $rowStatus === 'telat')
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-brand-amber border border-amber-200/80">
                                        <span class="w-2 h-2 rounded-full bg-brand-amber"></span>
                                        Check-in {{ substr($todayRecord->check_in, 0, 5) }} (telat)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-brand-muted border border-gray-200">
                                        <span class="w-2 h-2 rounded-full bg-brand-muted"></span>
                                        Belum absen
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <button type="button" @click="openEditModal({{ $p->id }}, {{ Illuminate\Support\Js::from($p->name) }}, {{ Illuminate\Support\Js::from($p->email) }})" aria-label="Edit {{ $p->name }}" title="Edit"
                                        class="p-2 text-brand-muted hover:text-brand-blue hover:bg-blue-50 border border-transparent hover:border-blue-100 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.peserta.destroy', $p) }}" onsubmit="return confirm('Hapus peserta {{ $p->name }}? Riwayat absensinya ikut terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" aria-label="Hapus {{ $p->name }}" title="Hapus"
                                            class="p-2 text-brand-muted hover:text-brand-orange hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p id="no-results-desktop" class="hidden text-center text-sm text-brand-muted py-10">Tidak ada peserta yang cocok</p>
        </div>

        <!-- Mobile Cards -->
        <div id="peserta-cards" class="block md:hidden divide-y divide-brand-border">
            @foreach ($peserta as $p)
                @php
                    $todayRecord = $p->attendances->first();
                    $hasCheckedIn = $todayRecord && $todayRecord->check_in;
                    $rowStatus = $hasCheckedIn ? $todayRecord->status : 'belum';
                    $initials = collect(explode(' ', $p->name))->map(fn ($w) => mb_substr($w, 0, 1))->join('');
                    $initials = mb_strtoupper(mb_substr($initials, 0, 2));
                @endphp
                <div class="p-4 space-y-3 bg-white"
                    data-name="{{ mb_strtolower($p->name) }}"
                    data-email="{{ mb_strtolower($p->email) }}"
                    data-status="{{ $rowStatus }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-blue to-brand-orange flex items-center justify-center text-white font-heading font-bold text-sm shadow-sm shrink-0">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="font-semibold text-brand-navy text-sm">{{ $p->name }}</div>
                                <div class="text-xs text-brand-muted">{{ $p->email }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="openEditModal({{ $p->id }}, {{ Illuminate\Support\Js::from($p->name) }}, {{ Illuminate\Support\Js::from($p->email) }})" class="p-1.5 text-brand-muted hover:text-brand-blue hover:bg-blue-50 rounded-md border border-gray-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                            <form method="POST" action="{{ route('admin.peserta.destroy', $p) }}" onsubmit="return confirm('Hapus peserta {{ $p->name }}? Riwayat absensinya ikut terhapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-brand-muted hover:text-brand-orange hover:bg-red-50 rounded-md border border-gray-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t border-gray-100 text-xs">
                        <span class="text-brand-muted">Terdaftar: <strong class="text-brand-navy font-medium">{{ $p->created_at->format('d/m/Y') }}</strong></span>
                        @if ($hasCheckedIn && $rowStatus === 'hadir')
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full font-semibold bg-emerald-50 text-brand-green border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-green"></span> Check-in {{ substr($todayRecord->check_in, 0, 5) }}
                            </span>
                        @elseif ($hasCheckedIn && $rowStatus === 'telat')
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full font-semibold bg-amber-50 text-brand-amber border border-amber-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span> Check-in {{ substr($todayRecord->check_in, 0, 5) }} (telat)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full font-semibold bg-gray-100 text-brand-muted border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-muted"></span> Belum absen
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
            <p id="no-results-mobile" class="hidden text-center text-sm text-brand-muted py-10 px-4">Tidak ada peserta yang cocok</p>
        </div>

        @if ($peserta->isEmpty())
            <p class="text-center text-sm text-brand-muted py-10">Belum ada peserta.</p>
        @endif

        @if ($peserta->hasPages())
            <div class="px-4 sm:px-6 py-3.5 bg-brand-bg/60 border-t border-brand-border flex items-center justify-between text-xs text-brand-muted">
                <div>Menampilkan <strong class="text-brand-navy font-semibold">{{ $peserta->firstItem() }}-{{ $peserta->lastItem() }}</strong> dari <strong class="text-brand-navy font-semibold">{{ $peserta->total() }}</strong> peserta</div>
                <div>{{ $peserta->links() }}</div>
            </div>
        @elseif ($peserta->isNotEmpty())
            <div class="px-4 sm:px-6 py-3.5 bg-brand-bg/60 border-t border-brand-border text-xs text-brand-muted">
                Menampilkan <strong class="text-brand-navy font-semibold">{{ $peserta->count() }}</strong> dari <strong class="text-brand-navy font-semibold">{{ $peserta->total() }}</strong> peserta
            </div>
        @endif
    </div>

    <!-- Modal: Tambah Peserta -->
    <div x-show="showAddModal" x-cloak
        @keydown.escape.window="showAddModal = false"
        @click.self="showAddModal = false"
        class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-[#071A3D]/70 backdrop-blur-md px-4 py-10">
        <div class="relative bg-white rounded-2xl shadow-2xl border {{ $errors->any() ? 'border-red-200' : 'border-brand-border/80' }} overflow-hidden w-full max-w-[460px] mt-4">

            <div class="h-1.5 w-full bg-gradient-to-r {{ $errors->any() ? 'from-brand-orange via-red-500 to-amber-500' : 'from-brand-blue via-brand-navy-light to-brand-orange' }}"></div>

            <div class="p-6 pb-4 flex items-start justify-between border-b border-brand-border/60">
                <div>
                    <div class="flex items-center space-x-2 mb-1">
                        @if ($errors->any())
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-brand-orange tracking-wide uppercase">Validasi diperlukan</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-[11px] text-brand-muted font-medium">Form error</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-brand-blue tracking-wide uppercase">Admin form</span>
                        @endif
                    </div>
                    <h2 class="font-heading font-bold text-xl text-brand-navy tracking-tight">Tambah peserta magang</h2>
                    <p class="text-xs text-brand-muted mt-1 leading-relaxed">Peserta baru bisa langsung login setelah disimpan.</p>
                </div>

                <button type="button" @click="showAddModal = false" class="w-8 h-8 -mr-1 -mt-1 rounded-full text-slate-400 hover:text-brand-navy hover:bg-slate-100 flex items-center justify-center transition" title="Tutup dialog">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            @if ($errors->any())
                <div class="mx-6 mt-4 p-3 bg-red-50/80 border border-red-200 rounded-xl flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-brand-orange shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-xs text-red-800 font-medium">Periksa kembali data formulir sebelum menyimpan.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.peserta.store') }}" class="p-6 space-y-5" @submit="saving = true">
                @csrf

                <div class="space-y-1">
                    <label for="nama" class="block text-xs font-semibold text-brand-navy tracking-wide">
                        Nama lengkap <span class="text-brand-orange">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" placeholder="Contoh: Rian Anggara" value="{{ old('nama') }}"
                        class="underline-input w-full text-sm font-medium text-brand-navy placeholder:text-slate-300 {{ $errors->has('nama') ? 'error' : '' }}">
                    @error('nama')
                        <p class="text-[11px] font-medium text-brand-orange flex items-center gap-1 pt-1">
                            <svg class="w-3 h-3 text-brand-orange shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="email" class="block text-xs font-semibold text-brand-navy tracking-wide">
                        Alamat email <span class="text-brand-orange">*</span>
                    </label>
                    <input type="email" id="email" name="email" placeholder="nama@detik.com atau email aktif" value="{{ old('email') }}"
                        class="underline-input w-full text-sm font-medium text-brand-navy placeholder:text-slate-300 {{ $errors->has('email') ? 'error' : '' }}">
                    @error('email')
                        <p class="text-[11px] font-medium text-brand-orange flex items-center gap-1 pt-1">
                            <svg class="w-3 h-3 text-brand-orange shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <p class="text-[11px] text-brand-muted flex items-center gap-1 pt-0.5">
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Digunakan untuk login peserta
                        </p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-semibold text-brand-navy tracking-wide">
                            Kata sandi awal <span class="text-brand-orange">*</span>
                        </label>
                        <button type="button" @click="generatePassword()" class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand-blue hover:text-brand-navy hover:underline transition group">
                            <svg class="w-3 h-3 text-brand-orange group-hover:rotate-45 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"></path>
                            </svg>
                            Buatkan otomatis
                        </button>
                    </div>

                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" x-model="password" placeholder="Minimal 8 karakter"
                            class="underline-input w-full text-sm font-medium text-brand-navy pr-9 font-mono {{ $errors->has('password') ? 'error' : '' }}">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-0 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-brand-navy transition" :title="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[11px] font-medium text-brand-orange flex items-center gap-1 pt-1">
                            <svg class="w-3 h-3 text-brand-orange shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <p class="text-[11px] text-brand-muted pt-0.5">Minimal 8 karakter, peserta bisa menggantinya nanti</p>
                    @enderror
                </div>

                <div class="bg-brand-bg border border-brand-border rounded-xl p-3.5 flex items-start gap-3">
                    <div class="w-5 h-5 rounded-full bg-blue-100 text-brand-blue flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Setelah disimpan, peserta akan otomatis mendapat role <span class="font-semibold text-brand-navy bg-white px-1.5 py-0.5 rounded border border-brand-border">Peserta</span> dan bisa langsung check-in mulai hari ini.
                    </p>
                </div>

                <div class="pt-2 border-t border-brand-border/60 flex items-center justify-end space-x-3">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2.5 text-xs font-semibold text-brand-muted hover:text-brand-navy transition rounded-lg hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="submit" :disabled="saving"
                        class="px-5 py-2.5 bg-brand-navy hover:bg-brand-blue active:scale-[0.99] text-white text-xs font-medium rounded-lg shadow-sm hover:shadow transition flex items-center gap-2 disabled:opacity-90 disabled:cursor-not-allowed">
                        <template x-if="!saving">
                            <span class="flex items-center gap-2">
                                <span>Simpan peserta</span>
                                <svg class="w-3.5 h-3.5 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </template>
                        <template x-if="saving">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin -ml-0.5 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Menyimpan...</span>
                            </span>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Peserta -->
    <div x-show="showEditModal" x-cloak
        @keydown.escape.window="showEditModal = false"
        @click.self="showEditModal = false"
        class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-[#071A3D]/70 backdrop-blur-md px-4 py-10">
        <div class="relative bg-white rounded-2xl shadow-2xl border {{ $errors->any() && old('user_id') ? 'border-red-200' : 'border-brand-border/80' }} overflow-hidden w-full max-w-[460px] mt-4">

            <div class="h-1.5 w-full bg-gradient-to-r {{ $errors->any() && old('user_id') ? 'from-brand-orange via-red-500 to-amber-500' : 'from-brand-blue via-brand-navy-light to-brand-orange' }}"></div>

            <div class="p-6 pb-4 flex items-start justify-between border-b border-brand-border/60">
                <div>
                    <div class="flex items-center space-x-2 mb-1">
                        @if ($errors->any() && old('user_id'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-brand-orange tracking-wide uppercase">Validasi diperlukan</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-[11px] text-brand-muted font-medium">Form error</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-brand-blue tracking-wide uppercase">Edit peserta</span>
                        @endif
                    </div>
                    <h2 class="font-heading font-bold text-xl text-brand-navy tracking-tight">Edit peserta magang</h2>
                    <p class="text-xs text-brand-muted mt-1 leading-relaxed">Perbarui data <span x-text="editing.nama || 'peserta'"></span>.</p>
                </div>

                <button type="button" @click="showEditModal = false" class="w-8 h-8 -mr-1 -mt-1 rounded-full text-slate-400 hover:text-brand-navy hover:bg-slate-100 flex items-center justify-center transition" title="Tutup dialog">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            @if ($errors->any() && old('user_id'))
                <div class="mx-6 mt-4 p-3 bg-red-50/80 border border-red-200 rounded-xl flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-brand-orange shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-xs text-red-800 font-medium">Periksa kembali data formulir sebelum menyimpan.</span>
                </div>
            @endif

            <form method="POST" :action="editing.id ? '{{ url('admin/peserta') }}/' + editing.id : '#'" class="p-6 space-y-5" @submit="editSaving = true">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" :value="editing.id">

                <div class="space-y-1">
                    <label for="edit-nama" class="block text-xs font-semibold text-brand-navy tracking-wide">
                        Nama lengkap <span class="text-brand-orange">*</span>
                    </label>
                    <input type="text" id="edit-nama" name="nama" x-model="editing.nama"
                        class="underline-input w-full text-sm font-medium text-brand-navy placeholder:text-slate-300 {{ $errors->has('nama') ? 'error' : '' }}">
                    @error('nama')
                        <p class="text-[11px] font-medium text-brand-orange flex items-center gap-1 pt-1">
                            <svg class="w-3 h-3 text-brand-orange shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="edit-email" class="block text-xs font-semibold text-brand-navy tracking-wide">
                        Alamat email <span class="text-brand-orange">*</span>
                    </label>
                    <input type="email" id="edit-email" name="email" x-model="editing.email"
                        class="underline-input w-full text-sm font-medium text-brand-navy placeholder:text-slate-300 {{ $errors->has('email') ? 'error' : '' }}">
                    @error('email')
                        <p class="text-[11px] font-medium text-brand-orange flex items-center gap-1 pt-1">
                            <svg class="w-3 h-3 text-brand-orange shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <p class="text-[11px] text-brand-muted flex items-center gap-1 pt-0.5">
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Digunakan untuk login peserta
                        </p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label for="edit-password" class="block text-xs font-semibold text-brand-navy tracking-wide">
                            Kata sandi baru <span class="text-brand-muted font-normal normal-case">(opsional)</span>
                        </label>
                        <button type="button" @click="generateEditPassword()" class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand-blue hover:text-brand-navy hover:underline transition group">
                            <svg class="w-3 h-3 text-brand-orange group-hover:rotate-45 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"></path>
                            </svg>
                            Buatkan otomatis
                        </button>
                    </div>

                    <div class="relative">
                        <input :type="editShowPassword ? 'text' : 'password'" id="edit-password" name="password" x-model="editPassword" placeholder="Kosongkan jika tidak ingin mengubah"
                            class="underline-input w-full text-sm font-medium text-brand-navy pr-9 font-mono {{ $errors->has('password') ? 'error' : '' }}">
                        <button type="button" @click="editShowPassword = !editShowPassword" class="absolute right-0 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-brand-navy transition" :title="editShowPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                            <svg x-show="!editShowPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="editShowPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[11px] font-medium text-brand-orange flex items-center gap-1 pt-1">
                            <svg class="w-3 h-3 text-brand-orange shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <p class="text-[11px] text-brand-muted pt-0.5">Kosongkan agar kata sandi peserta tidak berubah</p>
                    @enderror
                </div>

                <div class="pt-2 border-t border-brand-border/60 flex items-center justify-end space-x-3">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2.5 text-xs font-semibold text-brand-muted hover:text-brand-navy transition rounded-lg hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="submit" :disabled="editSaving"
                        class="px-5 py-2.5 bg-brand-navy hover:bg-brand-blue active:scale-[0.99] text-white text-xs font-medium rounded-lg shadow-sm hover:shadow transition flex items-center gap-2 disabled:opacity-90 disabled:cursor-not-allowed">
                        <template x-if="!editSaving">
                            <span class="flex items-center gap-2">
                                <span>Simpan perubahan</span>
                                <svg class="w-3.5 h-3.5 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </template>
                        <template x-if="editSaving">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin -ml-0.5 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Menyimpan...</span>
                            </span>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const search = document.getElementById('peserta-search');
            const statusFilter = document.getElementById('peserta-status-filter');
            const tableRows = document.querySelectorAll('#peserta-table-body tr');
            const cards = document.querySelectorAll('#peserta-cards > div[data-name]');
            const noResultsDesktop = document.getElementById('no-results-desktop');
            const noResultsMobile = document.getElementById('no-results-mobile');

            function matches(el, query, status) {
                const name = el.dataset.name || '';
                const email = el.dataset.email || '';
                const rowStatus = el.dataset.status || '';

                const matchesQuery = !query || name.includes(query) || email.includes(query);
                const matchesStatus = !status || rowStatus === status;

                return matchesQuery && matchesStatus;
            }

            function applyFilter() {
                const query = search.value.trim().toLowerCase();
                const status = statusFilter.value;

                let visibleDesktop = 0;
                tableRows.forEach(function (row) {
                    const show = matches(row, query, status);
                    row.classList.toggle('hidden', !show);
                    if (show) visibleDesktop++;
                });

                let visibleMobile = 0;
                cards.forEach(function (card) {
                    const show = matches(card, query, status);
                    card.classList.toggle('hidden', !show);
                    if (show) visibleMobile++;
                });

                noResultsDesktop.classList.toggle('hidden', visibleDesktop !== 0 || tableRows.length === 0);
                noResultsMobile.classList.toggle('hidden', visibleMobile !== 0 || cards.length === 0);
            }

            search.addEventListener('input', applyFilter);
            statusFilter.addEventListener('change', applyFilter);
        })();
    </script>
    </div>
</x-admin-layout>
