<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->text('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('thumbnails')->nullable();
            $table->longText('meta_description')->nullable();
            $table->json('content')->nullable();
            $table->string('layout')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('order')->default('9999');
            $table->bigInteger('password')->nullable();
            $table->timestamp('begin_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
