<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '0248593031',
            'password' => Hash::make('123456'),
        ]);

        User::create([
            'name' => 'Chief Selasi',
            'email' => 'selasi@example.com',
            'phone' => '0249747585',
            'password' => Hash::make('123456'),
        ]);

        User::create([
            'name' => 'Alice Johnson',
            'email' => 'alicejohnson@example.com',
            'phone' => '1122334455',
            'password' => Hash::make('123456'),
        ]);
    }
}
