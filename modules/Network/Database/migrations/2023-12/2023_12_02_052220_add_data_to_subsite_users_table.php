<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'subsite_users',
            function (Blueprint $table) {
                $table->json('json_metas')->nullable();
                $table->json('data')->nullable();
                $table->boolean('is_fake')->default(false);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'subsite_users',
            function (Blueprint $table) {
                $table->dropColumn(['json_metas', 'data', 'is_fake']);
            }
        );
    }
};
