<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Network\Models;

use Juzaweb\CMS\Models\Model;
use Juzaweb\Network\Interfaces\RootNetworkModelInterface;
use Juzaweb\Network\Traits\RootNetworkModel;

/**
 * Juzaweb\Network\Models\DomainMapping
 *
 * @property int $id
 * @property string $domain
 * @property int $site_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DomainMapping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DomainMapping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DomainMapping query()
 * @method static \Illuminate\Database\Eloquent\Builder|DomainMapping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomainMapping whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomainMapping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomainMapping whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomainMapping whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DomainMapping extends Model implements RootNetworkModelInterface
{
    use RootNetworkModel;

    protected $table = 'network_domain_mappings';

    protected $fillable = [
        'domain',
        'site_id',
    ];
}
