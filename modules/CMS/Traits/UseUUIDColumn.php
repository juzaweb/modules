<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    GNU General Public License v2.0
 */

namespace Juzaweb\CMS\Traits;

use Illuminate\Support\Str;
use Juzaweb\CMS\Models\Model;

trait UseUUIDColumn
{
    protected static function bootUseUUIDColumn(): void
    {
        /**
         * Listen for the creating event on the user model.
         * Sets the 'id' to a UUID using Str::uuid() on the instance being created
         */
        static::creating(
            function (Model $model) {
                if (empty($model->uuid) && $model->getKey() === null) {
                    $model->setAttribute('uuid', static::generateUniqueUUID());
                }
            }
        );
    }

    public static function generateUniqueUUID(): string
    {
        do {
            $uuid = Str::uuid()->toString();
        } while (static::withoutGlobalScopes()->where('uuid', $uuid)->exists());

        return $uuid;
    }
}
