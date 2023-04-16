<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            0 => [
                'id' => 1,
                'name' => 'Jack Kompass',
                'email' => 'admin@admin.de',
                'email_verified_at' => now(),
                'password' => bcrypt('123456'),
                'remember_token' => Str::random(10),
            ],
        ]);
    }
}
