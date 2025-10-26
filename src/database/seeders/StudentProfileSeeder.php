<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentProfile;

class StudentProfileSeeder extends Seeder
{
    public function run(): void
    {
        StudentProfile::insert([
            [
                'id' => 1,
                'user_id' => 1,
                'faculty' => 'Axborot texnologiyalari',
                'group_name' => 'G-21',
                'course' => '3-kurs',
                'tutor' => 'Olimov Sherzod',
                'gender' => 'male',
                'rent_area' => 'Chilonzor tumani',
                'rent_address' => 'Chilonzor 12-kvartal, 5-uy',
                'rent_map_url' => 'https://maps.google.com/example1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'faculty' => 'Filologiya',
                'group_name' => 'F-12',
                'course' => '2-kurs',
                'tutor' => 'Karimova Nodira',
                'gender'=> 'female',
                'rent_area' => 'Yakkasaroy tumani',
                'rent_address' => 'Yakkasaroy, Afrosiyob ko‘chasi 17',
                'rent_map_url' => 'https://maps.google.com/example2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'faculty' => 'Matematika',
                'group_name' => 'M-09',
                'course' => '1-kurs',
                'tutor' => 'Ismoilov Bekzod',
                'gender'=> 'male',
                'rent_area' => 'Mirzo Ulug‘bek tumani',
                'rent_address' => 'Buyuk Ipak Yo‘li 45',
                'rent_map_url' => 'https://maps.google.com/example3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
