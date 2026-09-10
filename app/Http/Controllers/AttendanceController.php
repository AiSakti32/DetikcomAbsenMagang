<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\GeofenceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request, GeofenceService $geofence): RedirectResponse
    {
        $user = $request->user();
        $now = now();

        if ($user->attendances()->whereDate('date', $now->toDateString())->exists()) {
            return back()->with('error', 'Anda sudah check-in hari ini.');
        }

        $validated = $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $radius = (int) config('absensi.radius_meters');
        $distance = $geofence->distanceMeters(
            (float) $validated['latitude'],
            (float) $validated['longitude'],
            (float) config('absensi.office_latitude'),
            (float) config('absensi.office_longitude'),
        );

        if ($distance > $radius) {
            return back()->with('error', "Kamu berada di luar area yang diizinkan untuk absen (jarak: {$distance}m, maksimal {$radius}m).");
        }

        $user->attendances()->create([
            'date' => $now->toDateString(),
            'check_in' => $now->format('H:i:s'),
            'status' => $now->format('H:i') > config('absensi.batas_checkin') ? 'telat' : 'hadir',
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'distance_meters' => $distance,
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
