<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected array $tableNames = [
        'posts',
        'taxonomies',
        'email_templates',
        'menus',
        'media_files',
        'media_folders',
        'notifications',
        'resources',
        'roles',
        'permissions',
        'configs',
        'comments',
        'seo_metas',
        'theme_configs',
        'languages',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        foreach ($this->tableNames as $tableName) {
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'site_id')) {
                        $table->foreignId('site_id')->nullable()
                            ->constrained('network_sites')
                            ->cascadeOnDelete();
                    }
                }
            );
        }

        // Edit unique key
        Schema::table(
            'configs',
            function (Blueprint $table) {
                $table->dropUnique(['code']);
                $table->unique(['code', 'site_id']);
            }
        );

        Schema::table(
            'theme_configs',
            function (Blueprint $table) {
                $table->dropUnique(['code', 'theme']);
                $table->unique(['code', 'theme', 'site_id']);
            }
        );
    }
};
