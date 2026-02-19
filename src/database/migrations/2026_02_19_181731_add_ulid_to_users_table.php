<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->ulid('ulid')->nullable()->after('id');
        });

        // Generate ULIDs for all existing users
        DB::table('users')->whereNull('ulid')->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'ulid' => (string) Str::ulid(),
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->ulid('ulid')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });
    }
};
