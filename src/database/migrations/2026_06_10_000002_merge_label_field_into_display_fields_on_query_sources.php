<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill display_fields from label_field where empty, then drop label_field.
        // display_fields is now the single source of truth; its first entry is the title.
        DB::table('query_sources')->get()->each(function ($row): void {
            $display = json_decode((string) $row->display_fields, true);

            if (! is_array($display) || $display === []) {
                DB::table('query_sources')
                    ->where('id', $row->id)
                    ->update(['display_fields' => json_encode([$row->label_field ?: 'title'])]);
            }
        });

        Schema::table('query_sources', function (Blueprint $table): void {
            $table->dropColumn('label_field');
        });
    }

    public function down(): void
    {
        Schema::table('query_sources', function (Blueprint $table): void {
            $table->string('label_field')->default('title')->after('model_key');
        });

        DB::table('query_sources')->get()->each(function ($row): void {
            $display = json_decode((string) $row->display_fields, true);
            $first = is_array($display) && $display !== [] ? $display[0] : 'title';

            DB::table('query_sources')
                ->where('id', $row->id)
                ->update(['label_field' => $first]);
        });
    }
};
