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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('anrede')->nullable();
            $table->string('titel')->nullable();
            $table->string('vorname')->nullable();
            $table->string('nachname')->nullable();
            $table->string('position')->nullable();
            $table->string('emailadresse')->nullable();
            $table->string('firma')->nullable();
            $table->string('adresse')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('status')->nullable();
            $table->string('werbung')->nullable();
            $table->string('begleitung')->nullable();
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
        Schema::dropIfExists('contacts');
    }
};
