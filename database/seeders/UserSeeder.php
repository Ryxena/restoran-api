<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Joko',
            'email' => 'joko@mail.com',
            'password' => Hash::make('password')
        ]);
        User::create([
            'name' => 'Budi',
            'email' => 'budi@mail.com',
            'password' => Hash::make('password123')
        ]);
        User::create([
            'name' => 'Andi',
            'email' => 'andi@mail.com',
            'password' => Hash::make('passwordjuga')
        ]);
    }
}
