<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Models;

use Juzaweb\CMS\Facades\GlobalData;
use Juzaweb\CMS\Models\Model;
use Juzaweb\Network\Traits\RootNetworkModel;

class NetworkConfig extends Model
{
    use RootNetworkModel;

    public $timestamps = false;

    protected $table = 'network_configs';

    protected $fillable = [
        'code',
        'value'
    ];

    public static function configs()
    {
        return apply_filters('configs', GlobalData::get('network_configs'));
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
