<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Demo Customer',
            'email' => 'customer@demo.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        User::create([
            'name' => 'Demo Mitra',
            'email' => 'mitra@demo.com',
            'password' => Hash::make('password'),
            'role' => 'mitra'
        ]);

        User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
}