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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Juzaweb\Network\Models\Database;

class MakeDatabaseCommand extends Command
{
    protected $name = 'network:make-database';

    protected $description = 'Create database for network.';

    public function handle(): void
    {
        $default = config('database.default');

        $connection = $this->ask('DB Connection', $default);
        $connectionDefaultConfigs = config("database.connections.{$connection}", []);

        $host = $this->ask('DB Host', config("database.connections.{$default}.host"));

        $port = $this->ask('DB Port', config("database.connections.{$default}.port"));

        $database = $this->ask('DB Name', config("database.connections.{$default}.database"));

        $username = $this->ask('DB Username', config("database.connections.{$default}.username"));

        $password = $this->ask('DB Password');

        $prefix = $this->ask('DB Prefix', config("database.connections.{$default}.prefix"));

        Config::set(
            'database.connections.test_connection',
            array_merge(
                $connectionDefaultConfigs,
                [
                    'driver' => $connection,
                    'host' => $host,
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'port' => $port,
                    'prefix' => $prefix,
                ]
            )
        );

        // Test connection
        try {
            DB::connection('test_connection')->getPdo();
        } catch (\Exception $e) {
            $this->error("Database connection failed: ".$e->getMessage());
            return;
        }

        // Create database
        Config::set('database.default', 'test_connection');

        $this->call('migrate', ['--force' => true]);

        $this->call('network:migrate');

        Config::set('database.default', $default);

        Database::create(
            [
                'dbconnection' => $connection,
                'dbname' => $database,
                'dbhost' => $host,
                'dbuser' => $username,
                'dbpass' => $password,
                'dbport' => $port,
                'dbprefix' => $prefix,
            ]
        );

        $this->info('Database created successfully.');
    }
}
