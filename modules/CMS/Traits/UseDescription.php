<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Traits;

use Illuminate\Support\Str;
use Juzaweb\CMS\Models\Model;

trait UseDescription
{
    public static function bootUseDescription(): void
    {
        static::saving(
            function ($model) {
                /** @var Model $model */
                if (!$model->getAttribute('description')) {
                    $model->description = seo_string($model->content, 190);
                }
            }
        );
    }

    public function getDescription($words = 24)
    {
        return apply_filters(
            $this->type . '.get_description',
            Str::words($this->description, $words),
            $words
        );
    }
}
