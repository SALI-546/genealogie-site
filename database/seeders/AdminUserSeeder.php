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
        User::create([
            'id' => 1, // Assurez-vous que l'ID 1 est disponible
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('$2y$12$ZrGmx9au4l9SN62ekTA14e1DuDmRMtoG9zmBiXLjeY5KnI2hV6RQ.'), 
        ]);
    }
}
