<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected array $tables = ['posts', 'taxonomies', 'email_templates', 'resources', 'menus'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = DB::getTablePrefix();
        foreach ($this->tables as $tb) {
            if (!Schema::hasColumn($tb, 'uuid')) {
                Schema::table(
                    $tb,
                    function (Blueprint $table) use ($tb, $prefix) {
                        $table->uuid()->nullable()->unique("{$prefix}_{$tb}_uuid_unique");
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
    public function down()
    {
        $prefix = DB::getTablePrefix();
        foreach ($this->tables as $tb) {
            Schema::table(
                $tb,
                function (Blueprint $table) use ($tb, $prefix) {
                    $table->dropUnique("{$prefix}_{$tb}_uuid_unique");
                    $table->dropColumn('uuid');
                }
            );
        }
    }
};
