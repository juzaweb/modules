<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Network\Support;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Support\Arr;
use Juzaweb\CMS\Models\User;
use Juzaweb\Network\Contracts\NetworkRegistionContract;
use Juzaweb\Network\Contracts\NetworkSiteContract;
use Juzaweb\Network\Contracts\SiteCreaterContract;
use Juzaweb\Network\Contracts\SiteManagerContract;
use Juzaweb\Network\Models\Site;

class SiteManager implements SiteManagerContract
{
    public function __construct(
        protected ConnectionResolverInterface $db,
        protected SiteCreaterContract $siteCreater,
        protected NetworkRegistionContract $networkRegistion
    ) {
    }

    public function find(string|int|Site $site): ?NetworkSiteContract
    {
        if (is_numeric($site)) {
            $site = Site::find($site);
        }

        if (is_string($site)) {
            $site = Site::where(['domain' => $site])->first();
        }

        if (empty($site)) {
            return null;
        }

        return $this->createSite($site);
    }

    public function findOrFail(string|int|Site $site): NetworkSiteContract
    {
        $site = $this->find($site);

        abort_unless($site, 404);

        return $site;
    }

    public function create(string $subdomain, array $args = [], ?User $user = null): NetworkSiteContract
    {
        $site = $this->siteCreater->create($subdomain, $args, $user);

        return $this->createSite($site);
    }

    public function getLoginUrl(string|int|Site $site, ?User $user = null): ?string
    {
        global $jw_user;
        $user = $user ?: $jw_user;
        return $this->find($site)?->getLoginUrl($user);
    }

    public function validateLoginUrl(array $data): null|bool|User
    {
        $token = Arr::get($data, 'token');
        $auth = Arr::get($data, 'auth');
        $user = json_decode(decrypt(urldecode(Arr::get($data, 'user'))), false, 512, JSON_THROW_ON_ERROR);

        if (empty($token) || empty($auth) || empty($user)) {
            return false;
        }

        if (generate_token($auth) != $token) {
            return false;
        }

        return User::findByEmail($user->email);
    }

    public function getCreater(): SiteCreaterContract
    {
        return $this->siteCreater;
    }

    public function currentSite(): ?NetworkSiteContract
    {
        return $this->find($this->networkRegistion->getCurrentSiteId());
    }

    private function createSite(Site $site): NetworkSiteContract
    {
        return app()->make(NetworkSite::class, ['site' => $site]);
    }
}
