<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $owner->assignRole('owner');

        $admin = User::create([
            'name' => 'Habibi',
            'email' => 'habibi@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('kepala-toko');

        $user = User::create([
            'name' => 'User',
            'email' => 'aqsal@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('karyawan');
        
    }
}
