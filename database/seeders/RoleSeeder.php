<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


// database/seeders/RoleSeeder.php
class RoleSeeder extends Seeder {
    public function run(): void {
        DB::table('roles')->insert([
            ['name' => 'super-admin'],
            ['name' => 'admin'],
            ['name' => 'user'],
        ]);
    }
}

