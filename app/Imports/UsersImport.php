<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if(!array_filter($row)) {
            return;
         } 

        $find = User::where('email', $row['Email'])->first();

        if ($find !== null) {
            return;
        }
        
        User::create([
            'name' => $row['Name'],
            'email' => $row['Email'],
            'gender' => $row['Gender'],
            'address' => $row['Address'],
            'phone' => $row['Phone'],
            'password' => Hash::make('iuranku'.$row['Name']),
            'role' => 'User',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
