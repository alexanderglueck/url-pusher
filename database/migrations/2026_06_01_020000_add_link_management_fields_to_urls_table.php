<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->string('push_status')->nullable()->after('title');
            $table->timestamp('pushed_at')->nullable()->after('push_status');
            $table->text('description')->nullable()->after('pushed_at');
            $table->text('image')->nullable()->after('description');
            $table->boolean('is_favorite')->default(false)->after('image');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'push_status',
                'pushed_at',
                'description',
                'image',
                'is_favorite',
            ]);
        });
    }
};
