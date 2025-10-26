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
            'name' => 'Diyorbek',
            'email' => 'abdisodiqovdiyorbek@gmail.com',
            'password' => Hash::make('supersecret'),
            'phone' => '+998901234001',
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Madina Rasulova',
            'email' => 'madina.rasulova@example.com',
            'password' => Hash::make('password123'),
            'phone' => '+998901234002',
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Jasur Abduvahobov',
            'email' => 'jasur.abduvahobov@example.com',
            'password' => Hash::make('password123'),
            'phone' => '+998901234003',
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Admin One',
            'email' => 'admin.admin@admin.com',
            'password' => Hash::make('admin123'),
            'phone' => '+998901234004',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin Two',
            'email' => 'admin.two@example.com',
            'password' => Hash::make('admin123'),
            'phone' => '+998901234005',
            'role' => 'admin',
        ]);

    }
}
