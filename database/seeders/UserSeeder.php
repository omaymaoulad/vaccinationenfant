<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Secteur;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Crée un utilisateur pour chaque secteur existant
        $secteurs = Secteur::all();

        foreach ($secteurs as $secteur) {
            User::create([
                'name' => 'Responsable ' . $secteur->nom,
                'email' => 'user' . $secteur->id . '@gmail.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'secteur_id' => $secteur->id,
            ]);
        }
    }    
}
