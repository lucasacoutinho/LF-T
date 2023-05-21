<?php

namespace Database\Seeders;

use App\Models\Task;
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
        User::factory()->has(Task::factory(10))->create([
            'name' => 'Liberfly',
            'email' => 'liberfly-test@auth.com',
            'password' => Hash::make('password'),
        ]);
    }
}
