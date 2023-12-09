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
            'network_databases',
            function (Blueprint $table) {
                $table->dropColumn(['active']);
            }
        );

        Schema::table(
            'network_databases',
            function (Blueprint $table) {
                $table->boolean('active')->default(true);
                $table->string('label')->nullable();
                $table->index('active');
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
            'network_databases',
            function (Blueprint $table) {
                $table->dropColumn(['label']);
                $table->dropIndex(['active']);
            }
        );
    }
};
