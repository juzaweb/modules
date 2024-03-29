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

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Traits\UseUUIDColumn;
use Juzaweb\Network\Facades\Network;
use Juzaweb\Network\Interfaces\RootNetworkModelInterface;
use Juzaweb\Network\Traits\RootNetworkModel;

/**
 * Juzaweb\Network\Models\Site
 *
 * @property int $id
 * @property string $subdomain
 * @property null|string $domain
 * @property string $status
 * @property int|null $db_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Site newModelQuery()
 * @method static Builder|Site newQuery()
 * @method static Builder|Site query()
 * @method static Builder|Site whereCreatedAt($value)
 * @method static Builder|Site whereDbId($value)
 * @method static Builder|Site whereDomain($value)
 * @method static Builder|Site whereId($value)
 * @method static Builder|Site whereStatus($value)
 * @method static Builder|Site whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|DomainMapping[] $domainMappings
 * @property-read int|null $domain_mappings_count
 */
class Site extends Model implements RootNetworkModelInterface
{
    use RootNetworkModel, UseUUIDColumn;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_VERIFICATION = 'verification';
    public const STATUS_BANNED = 'banned';

    protected $table = 'network_sites';

    protected $fillable = [
        'subdomain',
        'domain',
        'status',
        'db_id',
        'created_by',
    ];

    public static function getAllStatus(): array
    {
        return [
            self::STATUS_ACTIVE => trans('cms::app.active'),
            self::STATUS_INACTIVE => trans('cms::app.active'),
            self::STATUS_VERIFICATION => trans('cms::app.verification'),
            self::STATUS_BANNED => trans('cms::app.banned'),
        ];
    }

    public static function findByUUID(string $uuid): Site|null
    {
        return self::query()->where('uuid', $uuid)->first();
    }

    public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class, 'db_id', 'id');
    }

    public function users(): BelongsToMany
    {
        $connection = Network::getRootConnection();

        return $this->setConnection($connection)->belongsToMany(
            User::class,
            "network_site_user",
            'site_id',
            'user_id',
            'id',
            'id'
        );
    }

    public function domainMappings(): HasMany
    {
        return $this->hasMany(DomainMapping::class, 'site_id', 'id');
    }

    public function getFullDomain(): string
    {
        if ($this->domain) {
            return "{$this->subdomain}.{$this->domain}";
        }

        return "{$this->subdomain}.". config('network.domain');
    }

    public function getSiteUrl(string $path = null): string
    {
        return '//' . $this->getFullDomain() . '/'. ltrim($path, '/');
    }

    public function getFieldName(): string
    {
        return 'domain';
    }
}
