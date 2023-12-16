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

use Illuminate\Support\Str;
use Juzaweb\CMS\Models\User;
use Juzaweb\Network\Contracts\NetworkSiteContract;
use Juzaweb\Network\Models\Site;

/**
 * @method string getFullDomain()
 * @method string getSiteUrl()
 * @method static array getAllStatus()
 * @mixin Site
 */
class NetworkSite implements NetworkSiteContract
{
    protected Site $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function model(): Site
    {
        return $this->site;
    }

    public function getLoginUrl(User $user): string
    {
        $random = Str::random(5);
        $loginUrl = $this->getUrl(config('juzaweb.admin_prefix'));
        $string = "{$loginUrl}/{$random}";
        $token = generate_token($string);
        $user = encrypt(json_encode(['id' => $user->id], JSON_THROW_ON_ERROR));

        $data = [
            'token' => $token,
            'auth' => urldecode($string),
            'user' => urlencode($user)
        ];

        return apply_filters(
            'network.login_url',
            $loginUrl.'/token-login?'.http_build_query($data),
            $data,
            $this->site
        );
    }

    public function adminUrl(): string
    {
        return $this->getUrl(config('juzaweb.admin_prefix'));
    }

    public function getUrl(string $path = null): string
    {
        return $this->site->getSiteUrl($path);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->site->{$name}(...$arguments);
    }
}
