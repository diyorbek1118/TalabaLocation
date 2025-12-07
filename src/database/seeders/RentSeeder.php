<?php

namespace Database\Seeders;

use App\Models\Rent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rent::create([
            [
            'renter_id' => 1,
            'title' => 'Ijara uylar',
            'description' => 'sifatli uylar',
            'price' => 400000,
            'location' => 'samarqand'
            ],
            'renter_id' => 2,
            'title' => 'Kvartira uylar',
            'description' => 'eng yaxshi uylar',
            'price' => 300000,
            'location' => 'navoiy'
        ]);
    }
}
