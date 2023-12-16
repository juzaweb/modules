<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Juzaweb\CMS\Models\UserMeta;
use Juzaweb\Network\Facades\Network;
use Juzaweb\Network\Models\NetworkUserMeta;
use Juzaweb\Network\Observers\SubsiteModelObserver;
use Juzaweb\Network\Scopes\SubsiteQueryScope;

trait RootNetworkUser
{
    public static function bootRootNetworkUser(): void
    {
        if (static::applySubSiteScope()) {
            static::addGlobalScope(new SubsiteQueryScope());
            static::observe([SubsiteModelObserver::class]);
        }
    }

    public static function applySubSiteScope(): bool
    {
        return config('network.enable')
            && Network::isSubSite()
            && !in_array(
                Network::getCurrentSiteId(),
                explode(',', config('network.share_user_main_to_sites', ''))
            );
    }

    public function metas(): HasMany
    {
        if (static::applySubSiteScope()) {
            return $this->hasMany(NetworkUserMeta::class, 'user_id', 'id');
        }

        return $this->hasMany(UserMeta::class, 'user_id', 'id');
    }

    public function getTable(): string
    {
        if (static::applySubSiteScope()) {
            return 'subsite_users';
        }

        return parent::getTable();
    }

    public function cachePrefixValue(): string
    {
        if (static::applySubSiteScope()) {
            return $this->cachePrefix;
        }

        return 'subsite_users_';
    }

    public function getConnectionName(): ?string
    {
        if (static::applySubSiteScope()) {
            return 'subsite';
        }
        
        return parent::getConnectionName();
    }
}
