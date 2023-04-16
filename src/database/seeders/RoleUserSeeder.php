<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Secondnetwork\Kompass\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        User::all()->each(function ($user) use ($roles) {
            $user->roles()->attach(
                $roles->random(1)->pluck('id')
            );
        });
        DB::table('role_user')->where('id', '1')->update([
            'role_id' => '3',
            'user_id' => '1',
        ]);
    }
}
