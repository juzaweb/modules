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
    public function up(): void
    {
        Schema::table(
            'subsite_users',
            function (Blueprint $table) {
                $table->dropUnique(['email']);
                $table->index(['is_admin']);
                $table->index(['status']);
                $table->unique(['email', 'site_id']);
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
                $table->dropUnique(['email', 'site_id']);
                $table->dropIndex(['status']);
                $table->dropIndex(['is_admin']);
            }
        );
    }
};
