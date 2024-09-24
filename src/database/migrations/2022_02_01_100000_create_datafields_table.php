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
        Schema::create('datafields', function (Blueprint $table) {
            $table->id();
            $table->foreignId(('block_id'))->constrained()->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->integer('grid')->default('1');
            $table->integer('order')->default('1');
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('datafields');
    }
};
