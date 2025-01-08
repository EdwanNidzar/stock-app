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
        $admin = User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $manager = User::create([
            'name' => 'Habibi',
            'email' => 'habibi@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $manager->assignRole('manager');

        $user = User::create([
            'name' => 'User',
            'email' => 'aqsal@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('karyawan');
        
    }
}
