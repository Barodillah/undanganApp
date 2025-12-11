<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


// database/seeders/UserSeeder.php
class UserSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->insert([
            'role_id' => 1,
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@example.com',
            'phone' => '08123456789',
            'password' => bcrypt('password'),
            'created_at' => now(),
        ]);
    }
}

