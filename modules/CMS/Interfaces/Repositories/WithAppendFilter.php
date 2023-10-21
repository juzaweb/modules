<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    GNU General Public License v2.0
 */

namespace Juzaweb\CMS\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Builder;

interface WithAppendFilter
{
    public function appendCustomFilter(Builder $builder, array $input): Builder;
}
