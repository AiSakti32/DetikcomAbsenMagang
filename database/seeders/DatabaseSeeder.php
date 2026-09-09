<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accounts = [
            ['name' => 'Admin', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Peserta Satu', 'email' => 'peserta1@example.com', 'role' => 'peserta'],
            ['name' => 'Peserta Dua', 'email' => 'peserta2@example.com', 'role' => 'peserta'],
            ['name' => 'Peserta Tiga', 'email' => 'peserta3@example.com', 'role' => 'peserta'],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                ['name' => $account['name'], 'role' => $account['role'], 'password' => 'password']
            );
        }
    }
}
