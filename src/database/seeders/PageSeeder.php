<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Setting;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $page;

    public function run()
    {

        Setting::create([

            'name' => 'User can register',
            'key' => 'user-register',
            'type' => 'switch',
            'data' => '1',
            'group' => 'global',
            'order' => 1,

        ]);

        Setting::create([

            'name' => 'Single-Sign-On (SSO) (IN BETA)',
            'key' => 'sso',
            'type' => 'switch',
            'data' => '',
            'group' => 'global',
            'order' => 2,

        ]);

        Setting::create([

            'name' => 'Login Image',
            'key' => 'admin-bg-img',
            'type' => 'image',
            'data' => '',
            'group' => 'footer',
            'order' => 1,

        ]);

        Setting::create([

            'name' => 'Copytext',
            'key' => 'copytext',
            'type' => 'text',
            'data' => 'Copytext',
            'group' => 'footer',
            'order' => 1,

        ]);

        DB::table('menus')->insert([
            0 => [
                'id' => 1,
                'name' => 'Main',
                'slug' => 'main',
                'order' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Footer',
                'slug' => 'Footer',
                'order' => 1,
            ],
        ]);

        DB::table('menu_items')->insert([
            0 => [
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Home',
                'url' => '/',
                'target' => '_self',
                'order' => 1,
            ],
            1 => [
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Blog',
                'url' => '/blog',
                'target' => '_self',
                'order' => 2,
            ],
            2 => [
                'id' => 3,
                'menu_id' => 1,
                'title' => 'About',
                'url' => '/about',
                'target' => '_self',
                'order' => 3,
            ],
            3 => [
                'id' => 4,
                'menu_id' => 2,
                'title' => 'Home',
                'url' => '/',
                'target' => '_self',
                'order' => 1,
            ],
            4 => [
                'id' => 5,
                'menu_id' => 2,
                'title' => 'Blog',
                'url' => '/blog',
                'target' => '_self',
                'order' => 2,
            ],
            5 => [
                'id' => 6,
                'menu_id' => 2,
                'title' => 'About',
                'url' => '/about',
                'target' => '_self',
                'order' => 3,
            ],
        ]);

        $blockTypeData = ['layout' => 'popout', 'alignment' => 'left', 'slider' => ''];
        $this->page = Page::create([
            'title' => 'Home',
            'status' => 'published',
            'meta_description' => 'The Homepage',
            'order' => '999',
            'slug' => 'home',
            'layout' => 'is_front_page',
            'status' => 'published',
        ]);

        $block = $this->page->blocks()->create([
            'name' => 'Home',
            'set' => $blockTypeData,
            'status' => 'published',
            'grid' => '1',
            'iconclass' => 'blockquote',
            'type' => 'wysiwyg',
            'order' => '999',
        ]);

        Datafield::create([
            'block_id' => $block->id,
            'name' => 'wysiwyg',
            'type' => 'wysiwyg',
            'order' => '1',
            'data' => '{"time":1699101859852,"blocks":[{"id":"tDj43ofNgq","type":"header","data":{"text":"The Homepage","level":2}},{"id":"nB8EHgsYpy","type":"paragraph","data":{"text":"The wheel is come full circle. Harp not on that. I will no longer endure it, though yet I know no wise remedy how to avoid it. A fool, a fool! I met a fool i th forest, A motley fool. Invest me in my motley; give me leave To speak my mind, and I will through and through Cleanse the foul body of th infected world, If they will patiently receive my medicine. Then a soldier, Full of strange oaths, and bearded like the pard, Jealous in honour, sudden and quick in quarrel, Seeking the bubble reputation Even in the cannons mouth. "}}],"version":"2.28.0"}',
        ]);

        $this->page = Page::create([

            'title' => 'About',
            'status' => 'published',
            'meta_description' => 'The About',
            'order' => '999',
            'slug' => 'about',
            'layout' => 'is_front_page',
            'status' => 'published',
            // 'slug' => generateSlug($this->title)

        ]);
        $block = $this->page->blocks()->create([
            'name' => 'About',
            'set' => $blockTypeData,
            'status' => 'published',
            'grid' => '1',
            'iconclass' => 'blockquote',
            'type' => 'wysiwyg',
            'order' => '999',
        ]);

        Datafield::create([
            'block_id' => $block->id,
            'name' => 'wysiwyg',
            'type' => 'wysiwyg',
            'order' => '1',
            'data' => '{"time":1699101859852,"blocks":[{"id":"tDj43ofNgq","type":"header","data":{"text":"The About","level":2}},{"id":"nB8EHgsYpy","type":"paragraph","data":{"text":"The wheel is come full circle. Harp not on that. I will no longer endure it, though yet I know no wise remedy how to avoid it. A fool, a fool! I met a fool i th forest, A motley fool. Invest me in my motley; give me leave To speak my mind, and I will through and through Cleanse the foul body of th infected world, If they will patiently receive my medicine. Then a soldier, Full of strange oaths, and bearded like the pard, Jealous in honour, sudden and quick in quarrel, Seeking the bubble reputation Even in the cannons mouth."}}],"version":"2.28.0"}',
        ]);

    }
}
