<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create supervisor
        User::create([
            'name'     => 'Supervisor',
            'email'    => 'supervisor@ceres.com',
            'password' => Hash::make('password123'),
            'role'     => 'supervisor'
        ]);

        // Create assessor
        User::create([
            'name'     => 'John Assessor',
            'email'    => 'assessor@ceres.com',
            'password' => Hash::make('password123'),
            'role'     => 'assessor'
        ]);
    }
}
