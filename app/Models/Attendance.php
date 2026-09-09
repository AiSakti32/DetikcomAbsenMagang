<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Minutes past the configured check-in cutoff, for a "telat" record.
     * Returns null when the record isn't late (nothing to show).
     */
    public function lateMinutes(): ?int
    {
        if ($this->status !== 'telat' || ! $this->check_in) {
            return null;
        }

        $cutoff = Carbon::parse($this->date->format('Y-m-d').' '.config('absensi.batas_checkin'));
        $checkIn = Carbon::parse($this->date->format('Y-m-d').' '.$this->check_in);

        return max(0, (int) round($checkIn->diffInMinutes($cutoff, absolute: true)));
    }
}
