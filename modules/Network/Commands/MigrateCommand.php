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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Network\Facades\Network;

class MigrateCommand extends Command
{
    protected $name = 'network:migrate';

    protected $description = 'Migrate database network.';

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
        'post_views',
    ];

    public function handle(): void
    {
        foreach ($this->tableNames as $tableName) {
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'site_id')) {
                        $table->unsignedBigInteger('site_id')->index()->default(0);
                    }
                }
            );
        }

        try {
            Schema::table(
                'configs',
                function (Blueprint $table) {
                    $table->dropUnique(['code']);
                }
            );
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        // Edit unique key
        try {
            Schema::table(
                'configs',
                function (Blueprint $table) {
                    $table->unique(['code', 'site_id']);
                }
            );
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        try {
            Schema::table(
                'theme_configs',
                function (Blueprint $table) {
                    $table->dropUnique(['code', 'theme']);
                }
            );
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        try {
            Schema::table(
                'theme_configs',
                function (Blueprint $table) {
                    $table->unique(['code', 'theme', 'site_id']);
                }
            );
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        try {
            Schema::table(
                'languages',
                function (Blueprint $table) {
                    $table->dropUnique(['code']);
                }
            );
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        try {
            Schema::table(
                'languages',
                function (Blueprint $table) {
                    $table->unique(['code', 'site_id']);
                }
            );
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        $prefix = DB::connection(Network::getRootConnection())->getTablePrefix();

        try {
            Schema::table(
                'posts',
                function (Blueprint $table) {
                    $table->dropUnique(['slug']);
                }
            );
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        try {
            Schema::table(
                'posts',
                function (Blueprint $table) {
                    $table->unique(['slug', 'site_id']);
                }
            );
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        $exportTables = ['posts', 'taxonomies', 'email_templates', 'resources', 'menus'];
        foreach ($exportTables as $tb) {
            if (!Schema::hasColumn($tb, 'uuid')) {
                continue;
            }

            try {
                Schema::table(
                    $tb,
                    function (Blueprint $table) use ($tb, $prefix) {
                        $table->dropUnique("{$prefix}_{$tb}_uuid_unique");
                        $table->unique(['uuid', 'site_id']);
                    }
                );
            } catch (\Throwable $e) {
                $this->warn($e->getMessage());
            }
        }

        $this->info('Network database migrated.');
    }
}
