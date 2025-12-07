<?php

namespace Database\Seeders;

use App\Models\AdminProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminProfile::create([
            'user_id' =>1,
            'position' => 'superAdmin',
            'province' => 'Navoiy viloyati',
            'district' => 'Xatirchi tumani'
        ]);
    }
}
