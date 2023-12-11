<?php

namespace Juzaweb\CMS\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Juzaweb\CMS\Facades\GlobalData;
use Juzaweb\CMS\Interfaces\Models\ExportSupport;
use Juzaweb\CMS\Traits\Models\Exportable;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\CMS\Models\Config
 *
 * @property int $id
 * @property string $code
 * @property string|null $value
 * @method static Builder|Config newModelQuery()
 * @method static Builder|Config newQuery()
 * @method static Builder|Config query()
 * @method static Builder|Config whereCode($value)
 * @method static Builder|Config whereId($value)
 * @method static Builder|Config whereValue($value)
 * @mixin Eloquent
 * @property int|null $site_id
 * @method static Builder|Config whereSiteId($value)
 */
class Config extends Model implements ExportSupport
{
    use Networkable, Exportable;

    public $timestamps = false;
    protected $table = 'configs';
    protected $fillable = [
        'code',
        'value',
    ];

    public static function configs()
    {
        $configs = config('juzaweb.config');
        $configs = array_merge(GlobalData::get('configs'), $configs);
        return apply_filters('configs', $configs);
    }

    public static function getConfig($key, $default = null)
    {
        $value = self::where('code', '=', $key)->first();
        if (empty($value)) {
            return $default;
        }

        $value = $value->value;

        if (is_json($value)) {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        return $value;
    }
}
