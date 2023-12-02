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
    public static function bootNetworkable(): void
    {
        if (config('network.enable') && !Network::isRootSite()) {
            static::addGlobalScope(new SubsiteQueryScope());
            static::observe([SubsiteModelObserver::class]);
        }
    }

    public function metas(): HasMany
    {
        if (config('network.enable') && !Network::isRootSite()) {
            return $this->hasMany(NetworkUserMeta::class, 'user_id', 'id');
        }

        return $this->hasMany(UserMeta::class, 'user_id', 'id');
    }

    public function getTable(): string
    {
        if (config('network.enable') && !Network::isRootSite()) {
            return 'subsite_users';
        }

        return parent::getTable();
    }
}
