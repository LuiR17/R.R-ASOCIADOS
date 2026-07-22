<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default Admin User
        User::updateOrCreate(
            ['email' => 'admin@rryasociados.com'],
            [
                'name' => 'Administrador R.R Y Asociados',
                'password' => Hash::make('password123'),
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
