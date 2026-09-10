<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Batas Jam Check-in
    |--------------------------------------------------------------------------
    |
    | Check-in setelah jam ini akan otomatis berstatus "telat".
    |
    */
    'batas_checkin' => '08:00',

    /*
    |--------------------------------------------------------------------------
    | Mulai Jam Check-out
    |--------------------------------------------------------------------------
    |
    | Jam paling awal peserta boleh check-out. Informasional saja untuk saat
    | ini (ditampilkan di dashboard) — belum ditegakkan sebagai validasi.
    |
    */
    'mulai_checkout' => '16:00',

    /*
    |--------------------------------------------------------------------------
    | Geofencing Lokasi Kantor
    |--------------------------------------------------------------------------
    |
    | Titik koordinat kantor dan radius toleransi (dalam meter) yang dipakai
    | untuk memvalidasi lokasi peserta saat check-in.
    |
    */
    'office_latitude' => env('OFFICE_LATITUDE', -7.5663),
    'office_longitude' => env('OFFICE_LONGITUDE', 110.8281),
    'radius_meters' => env('OFFICE_RADIUS_METERS', 100),

];
