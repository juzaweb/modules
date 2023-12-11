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

/**
 * @method Builder exportFilter()
 * @method Builder scopeExportFilter(Builder $builder)
 */
trait Exportable
{
    public function exportableFields(): array
    {
        return $this->getFillable();
    }
}
