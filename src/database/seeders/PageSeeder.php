<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Secondnetwork\Kompass\Models\Page;
use Illuminate\Support\Facades\DB;
class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frontPage = new Page();
        $frontPage->title = 'Startpage';
        $frontPage->slug = 'startpage';
        $frontPage->layout = 'is_front_page';
        $frontPage->status = 'public';
        $frontPage->save();

        $frontPage = new Page();
        $frontPage->title = 'About';
        $frontPage->slug = 'about';
        $frontPage->status = 'public';
        $frontPage->save();

        DB::table('settings')->insert([
            0 => [
                'id' => 1,
                'name' => 'Copytext',
                'key' => 'copytext',
                'data' => 'secondnetwork',
                'group' => 'footer',
                'order' => 1
            ],
        ]);

        DB::table('menus')->insert([
            0 => [
                'id' => 1,
                'name' => 'Main',
                'slug' => 'main',
                'order' => 1
            ],
            1 => [
                'id' => 2,
                'name' => 'Footer',
                'slug' => 'Footer',
                'order' => 1
            ],
        ]);

        DB::table('menu_items')->insert([
            0 => [
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Home',
                'url' => '/',
                'target' => '_self',
                'order' => 1
            ],
            1 => [
                'id' => 2,
                'menu_id' => 1,
                'title' => 'About',
                'url' => '/about',
                'target' => '_self',
                'order' => 2
            ],
            2 => [
                'id' => 3,
                'menu_id' => 2,
                'title' => 'Home',
                'url' => '/',
                'target' => '_self',
                'order' => 1
            ],
            3 => [
                'id' => 4,
                'menu_id' => 2,
                'title' => 'About',
                'url' => '/about',
                'target' => '_self',
                'order' => 2
            ],
        ]);


        DB::table('blocktemplates')->insert([
            0 => [
                'id' => 1,
                'name' => 'Longtext',
                'slug' => 'longtext',
                'grid' => 1,
                'order' => 1
            ],
        ]);


        DB::table('blockfields')->insert([
            0 => [
                'id' => 1,
                'blocktemplate_id' => '1',
                'name' => 'Longtext',
                'slug' => 'longtext',
                'type' => 'wysiwyg',
                'grid' => 1,
                'order' => 1
            ],
        ]);

        DB::table('blocks')->insert([
            0 => [
                'id' => 1,
                'page_id' => 1,
                'name' => 'Longtext',
                'slug' => 'longtext',
                'status'  => 'public',
                'grid' => 1,
                'order' => 1,
            ],
            1 => [
                'id' => 2,
                'page_id' => 2,
                'name' => 'Longtext',
                'slug' => 'longtext',
                'status'  => 'public',
                'grid' => 1,
                'order' => 1,
            ],
        ]);


        DB::table('datafields')->insert([
            0 => [
                'id' => 1,
                'block_id' => 1,
                'name' => 'Longtext',
                'slug' => 'longtext',
                'type'  => 'wysiwyg',
                'grid' => 1,
                'order' => 1,
                'data'=> '<h1>Welcome to Kompass</h1><p>This is a Front Page</p>'
            ],
            1 => [
                'id' => 2,
                'block_id' => 2,
                'name' => 'Longtext',
                'slug' => 'longtext',
                'type'  => 'wysiwyg',
                'grid' => 1,
                'order' => 1,
                'data'=> '<h1>About Page</h1>'
            ],
        ]);


    }
}
