<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The legacy `api_token` guard has been replaced by Sanctum personal
     * access tokens, so the column is no longer needed.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['api_token']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token', 60)->unique()->nullable()->default(null);
        });
    }
};
