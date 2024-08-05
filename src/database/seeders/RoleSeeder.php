<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{


    public function run(): void
    {

        Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Administrator', // optional
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator', // optional
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Role::create([
            'name' => 'edtor',
            'display_name' => 'Edtor', // optional
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Role::create([
            'name' => 'user',
            'display_name' => 'User', // optional
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
