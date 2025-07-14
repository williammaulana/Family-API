<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super@familystore.com',
            'password' => Hash::make('123456'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Store',
            'email' => 'admin@familystore.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kasir Sari',
            'email' => 'kasir@familystore.com',
            'password' => Hash::make('123456'),
            'role' => 'cashier',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kasir Budi',
            'email' => 'budi@familystore.com',
            'password' => Hash::make('123456'),
            'role' => 'cashier',
            'is_active' => true,
        ]);
    }
}