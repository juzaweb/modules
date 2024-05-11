<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    protected array $tables = ['posts', 'taxonomies', 'resources'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasColumn($table, 'locale')) {
                Schema::table(
                    $table,
                    function (Blueprint $table) {
                        $table->string('locale')->nullable();
                    }
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'locale')) {
                Schema::table(
                    $table,
                    function (Blueprint $table) {
                        $table->dropColumn('locale');
                    }
                );
            }
        }
    }
};
