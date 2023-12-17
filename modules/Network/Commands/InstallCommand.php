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
use Illuminate\Support\Facades\DB;
use Juzaweb\Network\Contracts\SiteSetupContract;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command
{
    protected $name = 'network:install';

    protected $description = 'Install network.';

    public function handle(): void
    {
        if ($database = $this->argument('database')) {
            app()->make(SiteSetupContract::class)->setupDatabaseId($database);
        }

        $this->call('migrate', ['--force' => true]);

        $this->call(
            'migrate',
            [
                '--force' => true,
                '--path' => 'vendor/juzaweb/modules/modules/Network/Database/migrations/*',
            ]
        );

        $this->call('network:migrate');
    }

    public function getArguments(): array
    {
        return [
            ['database', InputArgument::OPTIONAL, 'The database connection to use'],
        ];
    }
}
