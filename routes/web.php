<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $monthly = $user->attendances()
        ->whereYear('date', now()->year)
        ->whereMonth('date', now()->month)
        ->get();

    $checkInSeconds = $monthly->pluck('check_in')->filter()->map(function ($time) {
        [$h, $m, $s] = explode(':', $time);
        return $h * 3600 + $m * 60 + $s;
    });

    return view('dashboard', [
        'today' => $user->attendances()->whereDate('date', today())->first(),
        'history' => $user->attendances()->latest('date')->get(),
        'totalHadir' => $monthly->where('status', 'hadir')->count(),
        'totalTelat' => $monthly->where('status', 'telat')->count(),
        'avgCheckIn' => $checkInSeconds->isNotEmpty()
            ? sprintf('%02d:%02d', intdiv((int) $checkInSeconds->avg(), 3600), intdiv((int) $checkInSeconds->avg() % 3600, 60))
            : '--:--',
    ]);
})->middleware(['auth', 'verified', 'role:peserta'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:peserta'])->group(function () {
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/report', [AdminController::class, 'report'])->name('report');
    Route::get('/report/export', [AdminController::class, 'export'])->name('report.export');

    Route::post('/peserta', [AdminController::class, 'store'])->name('peserta.store');
    Route::put('/peserta/{user}', [AdminController::class, 'update'])->name('peserta.update');
    Route::delete('/peserta/{user}', [AdminController::class, 'destroy'])->name('peserta.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
