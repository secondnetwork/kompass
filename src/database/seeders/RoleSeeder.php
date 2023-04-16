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
        $Userdb = new Role();
        $Userdb->name = 'User';
        $Userdb->slug = 'user';
        $Userdb->save();

        $Edtor = new Role();
        $Edtor->name = 'Edtor';
        $Edtor->slug = 'edtor';
        $Edtor->save();

        $admin = new Role();
        $admin->name = 'Admin';
        $admin->slug = 'admin';
        $admin->save();

        // $manager = new Role();
        // $manager->name = 'Project Manager';
        // $manager->slug = 'project-manager';
        // $manager->save();

        // $developer = new Role();
        // $developer->name = 'Web Developer';
        // $developer->slug = 'web-developer';
        // $developer->save();
    }
}
