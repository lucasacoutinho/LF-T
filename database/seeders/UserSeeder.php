<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Liberfly',
            'email' => 'liberfly-test@auth.com',
            'password' => Hash::make('password'),
        ]);
    }
}
