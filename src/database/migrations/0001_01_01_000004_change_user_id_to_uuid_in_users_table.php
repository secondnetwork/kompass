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
     *
     * @return void
     */
    public function up()
    {
        // First, modify the existing table to add the UUID column
        Schema::table('users', function (Blueprint $table): void {
            $table->uuid('new_id')->after('id')->index(); // Add new UUID column + index
        });

        // Copy existing data to the new UUID column
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update(['new_id' => (string) Str::uuid()]);
        }

        // Delete the old ID column
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('id'); // Delete old ID column
            $table->renameColumn('new_id', 'id'); // Rename new UUID column
            $table->primary('id'); // Set the UUID column as the Primary Key
        });

        Schema::table('sessions', function (Blueprint $table): void {
            // Check if the user_id column exists before attempting to delete the foreign key
            if (Schema::hasColumn('sessions', 'user_id')) {
                // Check if the foreign key exists before attempting to delete it
                $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'sessions' AND COLUMN_NAME = 'user_id'");
                if (! empty($foreignKeys)) {
                    $table->dropForeign(['user_id']); // Delete foreign key
                }
            }
            $table->dropColumn('user_id'); // Delete old user_id column

            // Add new user_id column as UUID
            $table->uuid('user_id')->nullable()->index(); // UUID for the user_id with index
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reversing this migration can be difficult in practice because
        // data loss could occur. A complete reversal is often not possible
        // or sensible.

        // **Example** (incomplete) reversal (use with caution!):

        Schema::table('sessions', function (Blueprint $table): void {
            $table->dropColumn('user_id'); // Remove UUID user_id
            $table->integer('user_id')->nullable()->unsigned(); // Restore integer user_id (adjust as needed)
            // You would need to restore the foreign key here, if possible.
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('id'); // Remove UUID id
            $table->increments('id'); // Restore integer id

            // You would need to restore the data here, if possible.
        });
    }
};
