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
        Schema::create('menus', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('group')->nullable();
            $table->string('slug')->nullable();
            $table->integer('order')->default('999');
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('menu_id')->nullable();
            $table->string('subgroup')->nullable();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('target')->default('_self');
            $table->string('iconclass')->nullable();
            $table->string('color')->nullable();
            $table->integer('order')->default('999');
        });

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_items');
        Schema::drop('menus');
    }
};
