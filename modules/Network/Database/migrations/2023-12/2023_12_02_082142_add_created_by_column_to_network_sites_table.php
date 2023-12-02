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
            'network_sites',
            function (Blueprint $table) {
                $table->foreignId('created_by')
                    ->nullable()
                    ->index()
                    ->constrained('users');
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
            'network_sites',
            function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn(['created_by']);
            }
        );
    }
};
