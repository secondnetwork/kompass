<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // blocks has no indexes beyond the primary key, so every page/post
        // block listing and every nested-children lookup is a full table scan.
        Schema::table('blocks', function (Blueprint $table): void {
            $table->index(['blockable_type', 'blockable_id', 'order'], 'blocks_blockable_order_index');
            $table->index(['subgroup', 'order'], 'blocks_subgroup_order_index');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->index('slug');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table): void {
            $table->dropIndex('blocks_blockable_order_index');
            $table->dropIndex('blocks_subgroup_order_index');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->dropIndex(['slug']);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropIndex(['slug']);
        });
    }
};
