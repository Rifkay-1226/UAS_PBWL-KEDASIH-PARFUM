<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@kedasih.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Pegawai user (optional)
        User::create([
            'name' => 'Pegawai',
            'email' => 'pegawai@kedasih.com',
            'password' => Hash::make('pegawai123'),
            'role' => 'pegawai',
        ]);
    }
}
