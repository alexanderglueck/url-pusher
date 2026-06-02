<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * The tables that gain a public ULID identifier.
     *
     * @var string[]
     */
    private array $tables = ['users', 'devices', 'urls'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            // 1. Add the column nullable and un-indexed so we can backfill.
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->char('ulid', 26)->nullable()->after('id');
            });

            // 2. Backfill every existing row with a fresh ULID. Lowercase to
            //    match HasUlids::newUniqueId() so all rows are consistent.
            DB::table($table)->orderBy('id')->select('id')->chunkById(500, function ($rows) use ($table) {
                foreach ($rows as $row) {
                    DB::table($table)
                        ->where('id', $row->id)
                        ->update(['ulid' => strtolower((string) Str::ulid())]);
                }
            });

            // 3. Lock it down: unique and not-null.
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->char('ulid', 26)->nullable(false)->change();
                $blueprint->unique('ulid');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->dropUnique($table.'_ulid_unique');
                $blueprint->dropColumn('ulid');
            });
        }
    }
};
