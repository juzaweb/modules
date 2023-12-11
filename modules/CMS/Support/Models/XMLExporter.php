<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use Juzaweb\CMS\Interfaces\Models\ExportSupport;
use Juzaweb\CMS\Models\Model;
use ReflectionClass;
use Symfony\Component\Finder\SplFileInfo;

class XMLExporter
{
    protected int $chunkSize = 500;

    protected array $modelPaths = [
        'vendor/juzaweb/modules/modules/*/Models',
        'plugins/*/src/Models',
    ];

    public function export(string $fileName)
    {
        $paths = [];
        foreach ($this->getModelPaths() as $modelPath) {
            $paths = array_merge($paths, glob(base_path($modelPath), GLOB_ONLYDIR));
        }

        $models = [];
        foreach ($paths as $path) {
            $models = array_merge(collect(File::allFiles($path))
                ->map(function (SplFileInfo $item) {
                    $path = $item->getRelativePathName();
                    $fileContents = File::get($item->getRealPath());
                    $namespace = preg_match('/namespace\s+(.*?);/', $fileContents, $matches) ? $matches[1] : null;
                    return "\\{$namespace}\\".substr($path, 0, strrpos($path, '.'));
                })
                ->filter(function ($class) {
                    $valid = false;
                    if (class_exists($class)) {
                        $reflection = new ReflectionClass($class);
                        $valid = $reflection->isSubclassOf(Model::class)
                            && !$reflection->isAbstract()
                            && $reflection->implementsInterface(ExportSupport::class);
                    }
                    return $valid;
                })
                ->values()
                ->toArray(),
                $models
            );
        }

        $xml = new \XMLWriter();

        $xml->openURI($fileName);

        $xml->startDocument('1.0');

        $xml->startElement('models');

        foreach ($models as $model) {
            $xml->startElement('model');
            $xml->writeElement('name', $model);
            $xml->startElement('data');
            $this->exportDataModel($model, $xml);
            $xml->endElement();
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();
        $xml->flush();
    }

    public function exportDataModel(string $model, \XMLWriter $xml): void
    {
        /** @var ExportSupport $model */
        $model = app()->make($model);
        $fields = $model->exportableFields();

        $model->newQuery()
            ->when(
                method_exists($model, 'scopeExportFilter'),
                fn (Builder $q) => $model->exportFilter()
            )
            ->chunkById(
                $this->getChunkSize(),
                function ($rows) use ($xml, $fields) {
                    foreach ($rows as $row) {
                        $xml->startElement('row');
                        foreach ($fields as $field) {
                            $xml->writeElement($field, $row->{$field});
                        }
                        $xml->endElement();
                    }
                }
            );
    }

    public function setChunkSize(int $chunkSize): static
    {
        $this->chunkSize = $chunkSize;

        return $this;
    }

    public function getChunkSize(): int
    {
        return $this->chunkSize;
    }

    public function setModelPaths(array $modelPaths): static
    {
        $this->modelPaths = $modelPaths;

        return $this;
    }

    public function getModelPaths(): array
    {
        return $this->modelPaths;
    }
}
