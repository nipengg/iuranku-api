<?php

namespace Database\Seeders;

use App\Models\TuitionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TuitionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TuitionType::create([
            'tuition_name' => 'Kebersihan'
        ]);

        TuitionType::create([
            'tuition_name' => 'Keamanan'
        ]);

        TuitionType::create([
            'tuition_name' => 'Kematian'
        ]);
    }
}
