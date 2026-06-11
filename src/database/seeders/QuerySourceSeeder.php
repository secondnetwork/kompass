<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Secondnetwork\Kompass\Models\QuerySource;

/**
 * Seeds the default relationship-block query sources (Pages, Blog posts) into the
 * query_sources table. These used to live in config('kompass.query_models');
 * they are now database-managed so admins can edit them in the backend.
 *
 * Idempotent: keyed by `key`, safe to re-run. The `model_key` values must exist
 * in config('kompass.query_source_models').
 */
class QuerySourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            [
                'key' => 'pages',
                'label' => 'Pages',
                'model_key' => 'pages',
                'display_fields' => ['title'],
                'order_fields' => ['created_at', 'updated_at', 'title'],
                'url_pattern' => '/{slug}',
                'status_filter' => 'published',
                'item_view' => 'relations.page',
                'wrapper_class' => 'grid gap-2',
                'with' => [],
                'order' => 1,
            ],
            [
                'key' => 'posts',
                'label' => 'Blog posts',
                'model_key' => 'posts',
                'display_fields' => ['title'],
                'order_fields' => ['created_at', 'updated_at', 'title'],
                'url_pattern' => '/blog/{slug}',
                'status_filter' => 'published',
                'item_view' => 'relations.post',
                'wrapper_class' => 'grid gap-6 sm:grid-cols-2 lg:grid-cols-3',
                'with' => ['category'],
                'order' => 2,
            ],
        ];

        foreach ($sources as $source) {
            QuerySource::updateOrCreate(['key' => $source['key']], $source);
        }
    }
}
