<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NetworkInstallCommand extends Command
{
    protected $name = 'network:install';

    protected $description = 'Install network.';

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

    public function handle(): void
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

        Schema::table(
            'languages',
            function (Blueprint $table) {
                $table->dropUnique(['code']);
                $table->unique(['code', 'site_id']);
            }
        );
    }
}
