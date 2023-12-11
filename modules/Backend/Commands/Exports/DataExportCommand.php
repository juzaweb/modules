<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Commands\Exports;

use Illuminate\Console\Command;
use Juzaweb\CMS\Support\Models\XMLExporter;

class DataExportCommand extends Command
{
    protected $name = 'cms:data-export';

    public function handle(): void
    {
        $this->info('Exporting data...');

        $exporter = new XMLExporter();

        $exporter->export(storage_path('app/data.xml'));
    }
}
