<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public function run(): void
    {
         User::create([
            'name' => 'John Doe',
            'email' => 'john2doe1@example.com',
            'password' => Hash::make('supersecret'),
            'phone' => '+998770561836',
            'role'=> 'student',
        ]);
    }
}
