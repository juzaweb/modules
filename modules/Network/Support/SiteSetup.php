<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Network\Support;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\ConnectionResolverInterface;
use Juzaweb\Network\Contracts\SiteSetupContract;
use Juzaweb\Network\Models\Database;

class SiteSetup implements SiteSetupContract
{
    protected static string $rootConnection;

    public function __construct(
        protected ConfigRepository $config,
        protected ConnectionResolverInterface $db
    ) {
    }

    public function setup(object $site): object
    {
        $site = $this->setupDatabase($site);
        $this->setupConfig($site);
        return $site;
    }

    public function setupConfig(object $site): void
    {
        if ($site->id) {
            $this->config->set('juzaweb.plugin.enable_upload', false);

            $this->config->set('juzaweb.theme.enable_upload', false);

            $this->config->set('session.cookie', "juzaweb_session_{$site->id}");

            // Default 1GB
            $this->config->set('juzaweb.filemanager.total_size', 1024 * 1024 * 1024);

            $this->setCachePrefix("jw_site_{$site->id}");

            $this->config->set('queue.connections.database.connection', $this->getRootConnection());
        }
    }

    public function setupDatabase(object $site): object
    {
        if ($site->db_id) {
            $this->setupDatabaseId($site->db_id);
        }

        $site->root_connection = $this->getRootConnection();

        return $site;
    }

    public function setupDatabaseId(int $dbId): void
    {
        $database = Database::find($dbId);

        throw_if($database === null, new \Exception('Database not found'));

        $connectionDefaultConfigs = config("database.connections.{$database->dbconnection}", []);

        $this->config->set(
            'database.connections.subsite',
            array_merge(
                $connectionDefaultConfigs,
                [
                    'driver' => $database->dbconnection,
                    'host' => $database->dbhost,
                    'database' => $database->dbname,
                    'username' => $database->dbuser,
                    'password' => $database->dbpass,
                    'port' => $database->dbport,
                    'prefix' => $database->dbprefix,
                ]
            )
        );

        static::$rootConnection = $this->getRootConnection();

        $this->config->set('database.default', 'subsite');

        $this->db->purge('subsite');
    }

    public function getRootConnection(): string
    {
        return static::$rootConnection ?? $this->db->getDefaultConnection();
    }

    protected function setCachePrefix($prefix): void
    {
        $this->config->set('cache.prefix', $prefix);

        $this->config->set('database.redis.options.prefix', $prefix);

        $this->config->set('juzaweb.cache_prefix', $prefix);
    }
}
