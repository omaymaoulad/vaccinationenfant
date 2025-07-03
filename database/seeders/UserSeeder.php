<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'=> 'Admin User',
            'email' => 'admin@gmail.com',
            'password'=> Hash::make('admin123'),
            'role' => 'admin',
        ]);
        User::create([
            'name'=> 'responsable sanitaire',
            'email' => 'user@gmail.com',
            'password'=> Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
