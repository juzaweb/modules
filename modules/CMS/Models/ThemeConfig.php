<?php

namespace Juzaweb\CMS\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\CMS\Models\ThemeConfig
 *
 * @property int $id
 * @property string $code
 * @property string $theme
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ThemeConfig newModelQuery()
 * @method static Builder|ThemeConfig newQuery()
 * @method static Builder|ThemeConfig query()
 * @method static Builder|ThemeConfig whereCode($value)
 * @method static Builder|ThemeConfig whereCreatedAt($value)
 * @method static Builder|ThemeConfig whereId($value)
 * @method static Builder|ThemeConfig whereTheme($value)
 * @method static Builder|ThemeConfig whereUpdatedAt($value)
 * @method static Builder|ThemeConfig whereValue($value)
 * @mixin Eloquent
 * @property int|null $site_id
 * @method static Builder|ThemeConfig whereSiteId($value)
 */
class ThemeConfig extends Model
{
    use Networkable;

    protected $table = 'theme_configs';

    protected $fillable = [
        'code',
        'theme',
        'value',
    ];
}
