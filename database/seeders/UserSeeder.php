<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'gender' => 'Male',
            'address' => '-',
            'phone' => '000000000000',
            'password' => Hash::make('123123'),
            'role' => 'Admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Neville',
            'email' => 'neville@example.com',
            'gender' => 'Male',
            'address' => 'Bogor',
            'phone' => '085217295708',
            'password' => Hash::make('123123'),
            'role' => 'User',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Damar',
            'email' => 'damar@example.com',
            'gender' => 'Male',
            'address' => 'Bekasi',
            'phone' => '082750192741',
            'password' => Hash::make('123123'),
            'role' => 'User',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
