<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Datafield;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US'); // Tech ist meist Englisch, 'de_DE' geht aber auch

        // 1. Tech-Schlagwörter definieren
        $techTopics = ['Laravel', 'React', 'Vue.js', 'Docker', 'Kubernetes', 'AWS', 'AI', 'ChatGPT', 'Tailwind CSS', 'PHP 8.2', 'MySQL', 'API Design'];
        $adjectives = ['Advanced', 'Beginner Guide to', 'The Future of', 'Scaling', 'Refactoring', 'Deploying', 'Understanding', 'Mastering'];
        
        for ($i = 0; $i < 10; $i++) {
            
            // 2. Zufälligen Tech-Titel bauen (z.B. "Mastering Laravel")
            $topic = $faker->randomElement($techTopics);
            $title = $faker->randomElement($adjectives) . ' ' . $topic;
            
            // Slug erstellen
            $slug = Str::slug($title) . '-' . ($i + 1);

            // Post erstellen
            $post = Post::create([
                'status' => 'published',
                'title' => $title,
                'slug' => $slug,
                'meta_description' => "Learn everything about $topic in this comprehensive guide.",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $block = $post->blocks()->create([
                'name' => 'Home',
                'set' => ['layout' => 'popout', 'alignment' => 'left', 'slider' => ''],
                'status' => 'published',
                'grid' => '1',
                'iconclass' => 'blockquote',
                'type' => 'wysiwyg',
                'order' => '999',
            ]);

            // 3. Tech-Content simulieren (JSON)
            // Wir fügen Code-Beispiele oder technische Begriffe in den Text ein
            $wysiwygData = [
                "time" => now()->timestamp * 1000,
                "blocks" => [
                    [
                        "id" => Str::random(10),
                        "type" => "header",
                        "data" => [
                            "text" => "Why use " . $topic . "?",
                            "level" => 2
                        ]
                    ],
                    [
                        "id" => Str::random(10),
                        "type" => "paragraph",
                        "data" => [
                            "text" => "When working with <b>$topic</b>, developers often face challenges regarding scalability and performance. " . $faker->paragraph(3)
                        ]
                    ],
                    [
                        "id" => Str::random(10),
                        "type" => "header", // Zwischenüberschrift
                        "data" => [
                            "text" => "Installation and Setup",
                            "level" => 3
                        ]
                    ],
                    [
                        "id" => Str::random(10),
                        "type" => "paragraph",
                        "data" => [
                            "text" => "To get started, make sure your environment is ready. " . $faker->sentence(10)
                        ]
                    ]
                ],
                "version" => "2.28.0"
            ];

            Datafield::create([
                'block_id' => $block->id,
                'type' => 'wysiwyg',
                'order' => '1',
                'data' => json_encode($wysiwygData),
            ]);
        }
    }
}