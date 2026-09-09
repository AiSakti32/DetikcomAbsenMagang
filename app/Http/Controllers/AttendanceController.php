<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request): RedirectResponse
    {
        $user = $request->user();
        $now = now();

        if ($user->attendances()->whereDate('date', $now->toDateString())->exists()) {
            return back()->with('error', 'Anda sudah check-in hari ini.');
        }

        $user->attendances()->create([
            'date' => $now->toDateString(),
            'check_in' => $now->format('H:i:s'),
            'status' => $now->format('H:i') > config('absensi.batas_checkin') ? 'telat' : 'hadir',
        ]);

        return back()->with('success', 'Check-in berhasil.');
    }

    public function checkOut(Request $request): RedirectResponse
    {
        $user = $request->user();

        $attendance = $user->attendances()
            ->whereDate('date', now()->toDateString())
            ->whereNull('check_out')
            ->first();

        if (! $attendance) {
            return back()->with('error', 'Anda belum check-in atau sudah check-out hari ini.');
        }

        $attendance->update(['check_out' => now()->format('H:i:s')]);

        return back()->with('success', 'Check-out berhasil.');
    }
}
