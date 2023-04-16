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
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('role_id');
            // $table->unsignedBigInteger('user_id');
            $table->foreignId(('role_id'))->constrained()->onDelete('cascade');
            $table->foreignId(('user_id'))->constrained()->onDelete('cascade');
            $table->timestamps();

            // $table->foreign(('role_id'))->references('id')->on('roles')->onDelete('cascade');
            // $table->foreign(('user_id'))->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('roles_user' ,function (Blueprint $table) {
        //     $table->dropForeign(('role_user_role_id_foreign'));
        //     $table->dropForeign(('role_user_user_id_foreign'));
        // });
        Schema::dropIfExists('role_user');
    }
};
