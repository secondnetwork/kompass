<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Secondnetwork\Kompass\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator', // optional
            'description' => 'User is allowed to manage and edit other users', // optional
        ]);

        Role::create([
            'name' => 'user',
            'display_name' => 'User', // optional
            'description' => '', // optional
        ]);

        Role::create([
            'name' => 'edtor',
            'display_name' => 'Edtor', // optional
            'description' => '', // optional
        ]);

    }
}
