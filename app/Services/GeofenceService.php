<?php

namespace App\Services;

class GeofenceService
{
    /**
     * Radius bumi dalam meter, dipakai untuk rumus Haversine.
     */
    private const EARTH_RADIUS_METERS = 6371000;

    /**
     * Hitung jarak antara dua titik koordinat (dalam meter) menggunakan
     * rumus Haversine, dibulatkan 2 desimal.
     */
    public function distanceMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $a = sin($latDelta / 2) ** 2
            + cos($latFrom) * cos($latTo) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = self::EARTH_RADIUS_METERS * $c;

        return round($distance, 2);
    }

    /**
     * Cek apakah titik koordinat berada dalam radius (meter) dari lokasi
     * kantor yang dikonfigurasi di config('absensi').
     */
    public function isInsideRadius(float $lat, float $lng, int $radiusMeters): bool
    {
        $officeLat = (float) config('absensi.office_latitude');
        $officeLng = (float) config('absensi.office_longitude');

        return $this->distanceMeters($lat, $lng, $officeLat, $officeLng) <= $radiusMeters;
    }
}
