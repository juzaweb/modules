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

class InstallCommand extends Command
{
    protected $name = 'network:install';

    protected $description = 'Install network.';

    public function handle(): void
    {
        $this->call(
            'migrate',
            [
                '--force' => true,
                '--path' => 'vendor/juzaweb/modules/modules/Network/Database/migrations/*',
            ]
        );

        $this->call('network:migrate');
    }
}
