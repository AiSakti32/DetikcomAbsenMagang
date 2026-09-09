<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    public function index(): View
    {
        $pesertaIds = User::where('role', 'peserta')->pluck('id');
        $totalPeserta = $pesertaIds->count();

        $todayAttendances = Attendance::whereIn('user_id', $pesertaIds)
            ->whereDate('date', today())
            ->get();

        $sudahCheckIn = $todayAttendances->whereNotNull('check_in')->count();

        $stats = [
            'total' => $totalPeserta,
            'sudahCheckIn' => $sudahCheckIn,
            'terlambat' => $todayAttendances->where('status', 'telat')->count(),
            'belumAbsen' => $totalPeserta - $sudahCheckIn,
            'persenCheckIn' => $totalPeserta > 0 ? round($sudahCheckIn / $totalPeserta * 100, 1) : 0,
        ];

        $peserta = User::where('role', 'peserta')
            ->with(['attendances' => fn ($q) => $q->whereDate('date', today())])
            ->latest()
            ->paginate(15);

        return view('admin.index', compact('peserta', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'peserta',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'peserta', 404);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $validated['nama'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Peserta berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === 'peserta', 404);

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Peserta berhasil dihapus.');
    }

    public function report(Request $request): View
    {
        // Date + name filters only (no status) — used for the stat cards,
        // average check-in and insight so those reflect the full filtered
        // range regardless of which status the table itself is narrowed to.
        $scoped = $this->filteredQuery($request);

        $total = (clone $scoped)->count();
        $hadir = (clone $scoped)->where('status', 'hadir')->count();
        $telat = (clone $scoped)->where('status', 'telat')->count();
        $alpha = (clone $scoped)->where('status', 'alpha')->count();

        $checkInSeconds = (clone $scoped)
            ->whereIn('status', ['hadir', 'telat'])
            ->whereNotNull('check_in')
            ->pluck('check_in')
            ->map(fn ($time) => $this->timeToSeconds($time));

        $avgCheckIn = null;
        $avgCheckInLabel = null;

        if ($checkInSeconds->isNotEmpty()) {
            $avgSeconds = (int) round($checkInSeconds->avg());
            $avgCheckIn = $this->secondsToTime($avgSeconds);
            $avgHour = intdiv($avgSeconds, 3600);
            $avgCheckInLabel = match (true) {
                $avgHour < 11 => 'Pagi',
                $avgHour < 15 => 'Siang',
                $avgHour < 18 => 'Sore',
                default => 'Malam',
            };
        }

        $summary = [
            'total' => $total,
            'hadir' => $hadir,
            'telat' => $telat,
            'alpha' => $alpha,
            'persenHadir' => $total > 0 ? round($hadir / $total * 100) : 0,
            'persenTelat' => $total > 0 ? round($telat / $total * 100) : 0,
            'persenAlpha' => $total > 0 ? round($alpha / $total * 100) : 0,
            'avgCheckIn' => $avgCheckIn,
            'avgCheckInLabel' => $avgCheckInLabel,
        ];

        $mostLate = (clone $scoped)
            ->where('status', 'telat')
            ->select('user_id', DB::raw('COUNT(*) as telat_count'))
            ->groupBy('user_id')
            ->orderByDesc('telat_count')
            ->with('user')
            ->first();

        $tableQuery = clone $scoped;

        if ($request->filled('status')) {
            $tableQuery->where('status', $request->query('status'));
        }

        $attendances = $tableQuery->latest('date')->paginate(15)->withQueryString();

        $periode = $request->filled('tanggal') ? null : $request->query('periode', 'hari_ini');
        $periodeLabel = $this->periodeLabel($request);

        return view('admin.report', compact('attendances', 'summary', 'mostLate', 'periode', 'periodeLabel'));
    }

    public function export(Request $request): BinaryFileResponse
    {
        $query = $this->filteredQuery($request);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        return Excel::download(
            new AttendanceExport($query->latest('date')),
            'laporan-absensi-'.now()->format('Y-m-d-His').'.xlsx'
        );
    }

    private function filteredQuery(Request $request): Builder
    {
        $query = Attendance::query()->with('user');

        if ($request->filled('tanggal')) {
            $query->whereDate('date', $request->query('tanggal'));
        } else {
            [$dari, $sampai] = $this->resolvePeriodeRange($request->query('periode', 'hari_ini'));

            if ($dari && $sampai) {
                $query->whereBetween('date', [$dari->toDateString(), $sampai->toDateString()]);
            }
        }

        if ($request->filled('nama')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->query('nama').'%'));
        }

        return $query;
    }

    /**
     * @return array{0: ?Carbon, 1: ?Carbon}
     */
    private function resolvePeriodeRange(string $periode): array
    {
        $today = Carbon::today();

        return match ($periode) {
            'hari_ini' => [$today->copy(), $today->copy()],
            'minggu_ini' => [$today->copy()->subDays(6), $today->copy()],
            'semua' => [null, null],
            default => [$today->copy()->startOfMonth(), $today->copy()],
        };
    }

    private function periodeLabel(Request $request): string
    {
        if ($request->filled('tanggal')) {
            return Carbon::parse($request->query('tanggal'))->locale('id')->translatedFormat('d M Y');
        }

        $today = Carbon::today();

        return match ($request->query('periode', 'hari_ini')) {
            'hari_ini' => 'Hari ini, '.$today->locale('id')->translatedFormat('d M Y'),
            'minggu_ini' => $today->copy()->subDays(6)->locale('id')->translatedFormat('d M').' – '.$today->locale('id')->translatedFormat('d M Y'),
            'semua' => 'Semua waktu',
            default => $today->locale('id')->translatedFormat('F Y'),
        };
    }

    private function timeToSeconds(string $time): int
    {
        [$h, $m, $s] = explode(':', $time);

        return ((int) $h) * 3600 + ((int) $m) * 60 + (int) $s;
    }

    private function secondsToTime(int $seconds): string
    {
        return sprintf('%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60));
    }
}
