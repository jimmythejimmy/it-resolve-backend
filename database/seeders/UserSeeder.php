<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@itresolve.com',
            'phone' => '081111111111',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name' => 'Teknisi IT',
            'email' => 'teknisi@itresolve.com',
            'phone' => '082222222222',
            'role' => 'teknisi',
            'is_active' => true,
            'password' => Hash::make('teknisi123'),
        ]);

        User::create([
            'name' => 'Staff Kantor',
            'email' => 'staff@itresolve.com',
            'phone' => '083333333333',
            'role' => 'staff',
            'is_active' => true,
            'password' => Hash::make('staff123'),
        ]);
    }
}