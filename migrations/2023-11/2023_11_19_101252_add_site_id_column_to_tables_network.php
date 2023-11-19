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
    }
};
