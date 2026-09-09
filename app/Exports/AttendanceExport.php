<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Builder $query) {}

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama', 'Check In', 'Check Out', 'Status'];
    }

    public function map($attendance): array
    {
        /** @var Attendance $attendance */
        return [
            $attendance->date->format('d/m/Y'),
            $attendance->user->name,
            $attendance->check_in ?? '-',
            $attendance->check_out ?? '-',
            ucfirst($attendance->status),
        ];
    }
}
