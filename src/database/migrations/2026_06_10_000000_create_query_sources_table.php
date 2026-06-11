<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('query_sources', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();        // used as the block's query-model meta value
            $table->string('label');
            $table->string('model_key');             // key into config('kompass.query_source_models')
            $table->string('label_field')->default('title');
            $table->json('order_fields')->nullable();
            $table->string('url_pattern')->nullable();
            $table->string('status_filter')->nullable();
            $table->string('item_view')->nullable();
            $table->string('wrapper_class')->nullable();
            $table->json('with')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('query_sources');
    }
};
