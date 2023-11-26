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

use Illuminate\Database\Eloquent\Builder;
use Juzaweb\CMS\Models\Model;

class Database extends Model
{
    protected $table = 'network_databases';

    protected $fillable = [
        'dbconnection',
        'dbname',
        'dbhost',
        'dbuser',
        'dbpass',
        'dbport',
        'dbprefix',
        'count',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }
}
