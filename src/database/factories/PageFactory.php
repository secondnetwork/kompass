<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => 'public',
            'title' => $this->faker->realText(100),
            'slug' => Str::slug($this->faker->realText(100), '-'),
            // 'thumbnails' => 'https://picsum.photos/200/300?random='.rand(1,500),
            'thumbnails' => 'https://picsum.photos/id/'.rand(1, 500).'/500/500',

            // 'thumbnails' => $this->faker->image('public/storage/images',640,480, null, false),
            'meta_description' => $this->faker->realText(100),
            // 'layout' => $this->faker->randomHtml(2,3),
        ];
    }
}
