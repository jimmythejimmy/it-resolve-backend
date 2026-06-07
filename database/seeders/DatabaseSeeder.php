<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jalankan seeder user yang sudah kamu buat tadi
        $this->call(UserSeeder::class);

        // 2. Panggil pabrik aset untuk membuat 10 data otomatis
        Asset::factory()->count(10)->create();
    }
}