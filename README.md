# Absensi Magang

Aplikasi absensi peserta magang berbasis Laravel 11 + Breeze (Blade). Peserta check-in/check-out harian, admin kelola peserta dan lihat/filter/export laporan absensi.

## Requirement

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL

## Instalasi

```bash
composer install
npm install
```

Salin `.env.example` menjadi `.env`, lalu set koneksi database MySQL (buat database-nya dulu, mis. `absensi_magang`):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_magang
DB_USERNAME=root
DB_PASSWORD=
```

Generate app key, migrate, dan seed data demo:

```bash
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Akun Demo

Semua akun seeder memakai password `password`.

| Role    | Email                  | Password |
|---------|-------------------------|----------|
| Admin   | admin@example.com       | password |
| Peserta | peserta1@example.com    | password |
| Peserta | peserta2@example.com    | password |
| Peserta | peserta3@example.com    | password |

Setelah login, admin diarahkan ke `/admin`, peserta ke `/dashboard`.

## Fitur

- **Peserta**: check-in/check-out harian (max 1x masing-masing per hari), status otomatis `hadir`/`telat` (telat jika check-in > 08:00), riwayat absensi pribadi.
- **Admin**: daftar & CRUD peserta (`/admin`), laporan absensi dengan filter tanggal/nama/status + summary card (`/admin/report`), export laporan ke Excel (`/admin/report/export`, mengikuti filter aktif).
- Role `admin`/`peserta` di-enforce lewat middleware (`role:admin`, `role:peserta`).

## Auto-mark Alpha

Peserta yang tidak check-in pada suatu hari bisa ditandai `alpha` lewat command:

```bash
php artisan attendance:mark-alpha        # untuk hari ini
php artisan attendance:mark-alpha 2026-09-01
```

Command ini dijadwalkan jalan otomatis tiap hari jam 23:59 (lihat `routes/console.php`). Scheduler Laravel butuh sesuatu yang memanggil `schedule:run` tiap menit — **ini tidak jalan otomatis dari `php artisan serve`**. Untuk produksi/server, tambahkan cron entry:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Di Windows, buat Task Scheduler job yang menjalankan `php artisan schedule:run` tiap menit.
