<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Juzaweb\CMS\Models\Model;

/**
 * @method Builder exportFilter()
 * @method array exportFormater()
 * @method Builder scopeExportFilter(Builder $builder)
 */
trait Exportable
{
    public function exportWith(): array
    {
        return [];
    }

    public function exportableFields(): array
    {
        return array_filter($this->getFillable(), function ($key) {
            return !str_contains($key, '_id');
        });
    }

    public function defaultExportFormater(): array
    {
        return Arr::only($this->toArray(), $this->exportableFields());
    }
}
