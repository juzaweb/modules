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
use Illuminate\Support\Facades\DB;
use Juzaweb\Network\Contracts\SiteSetupContract;

class SiteSetup implements SiteSetupContract
{
    protected ConfigRepository $config;

    protected ConnectionResolverInterface $db;

    public function __construct(
        ConfigRepository $config,
        ConnectionResolverInterface $db
    ) {
        $this->config = $config;

        $this->db = $db;
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

            $this->setCachePrefix("jw_site_{$site->id}");
        }
    }

    public function setupDatabase(object $site): object
    {
        $rootConnection = $this->db->getDefaultConnection();

        if ($site->db_id) {
            $database = DB::table('network_databases')->where('id', $site->db_id)->first();

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

            $this->config->set('database.default', 'subsite');

            $this->db->purge('subsite');
        }

        $site->root_connection = $rootConnection;

        return $site;
    }

    protected function setCachePrefix($prefix): void
    {
        $this->config->set('cache.prefix', $prefix);

        $this->config->set('database.redis.options.prefix', $prefix);

        $this->config->set('juzaweb.cache_prefix', $prefix);
    }
}
