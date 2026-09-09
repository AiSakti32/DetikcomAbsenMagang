<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Console\Command;

class MarkAttendanceAlpha extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-alpha {date? : Tanggal (Y-m-d), default hari ini}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tandai alpha peserta yang belum check-in pada tanggal tertentu';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $date = $this->argument('date') ?? today()->toDateString();

        $checkedInUserIds = Attendance::whereDate('date', $date)->pluck('user_id');

        $absentees = User::where('role', 'peserta')
            ->whereNotIn('id', $checkedInUserIds)
            ->get();

        foreach ($absentees as $user) {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $date,
                'status' => 'alpha',
            ]);
        }

        $this->info("Ditandai alpha: {$absentees->count()} peserta untuk tanggal {$date}.");

        return self::SUCCESS;
    }
}
