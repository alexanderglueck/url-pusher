<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            // Drop the cascading FK that was hard-deleting pushes (bypassing
            // the Url soft-delete) whenever their device was removed.
            $table->dropForeign(['device_id']);
        });

        Schema::table('urls', function (Blueprint $table) {
            $table->unsignedBigInteger('device_id')->nullable()->change();
        });

        Schema::table('urls', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->dropForeign(['device_id']);
        });

        Schema::table('urls', function (Blueprint $table) {
            $table->unsignedBigInteger('device_id')->nullable(false)->change();
        });

        Schema::table('urls', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
};
