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

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use Juzaweb\Network\Contracts\NetworkRegistionContract;
use Juzaweb\Network\Contracts\SiteSetupContract;
use Juzaweb\Network\Models\Site;
use Illuminate\Support\Facades\URL;
use Illuminate\Session\SessionManager;

class NetworkRegistion implements NetworkRegistionContract
{
    protected Application $app;

    protected ConfigRepository $config;

    protected CacheManager $cache;

    protected Request $request;

    protected DatabaseManager $db;

    protected SiteSetupContract $siteSetup;

    protected Kernel $kernel;

    protected SessionManager $session;

    protected UrlGenerator $url;

    protected ?object $site;

    public function __construct(
        Application $app,
        ConfigRepository $config,
        Request $request,
        CacheManager $cache,
        DatabaseManager $db,
        SiteSetupContract $siteSetup,
        Kernel $kernel,
        SessionManager $session,
        UrlGenerator $url
    ) {
        $this->app = $app;
        $this->config = $config;
        $this->cache = $cache;
        $this->request = $request;
        $this->db = $db;
        $this->siteSetup = $siteSetup;
        $this->kernel = $kernel;
        $this->session = $session;
        $this->url = $url;
    }

    public function init(?string $site = null): void
    {
        if ($site === null && $this->app->runningInConsole()) {
            $this->initConsole();
        } else {
            $this->initWeb($site);
        }

        $GLOBALS['site'] = $this->site;
    }

    public function getCurrentSite(): ?object
    {
        return $this->site;
    }

    public function isRootSite(string $domain = null): bool
    {
        if (empty($domain)) {
            return $this->site->id === 0;
        }

        return $this->isRootDomain($domain);
    }

    public function isSubSite(string $domain = null): bool
    {
        return !$this->isRootSite($domain);
    }

    public function getCurrentDomain(): string
    {
        return $this->request->getHttpHost();
    }

    public function getCurrentSiteId(): int
    {
        return $this->getCurrentSite()->id;
    }

    public function getRootConnection(): string
    {
        return $this->siteSetup->getRootConnection();
    }

    protected function initWeb(?string $site = null): void
    {
        if ($site !== null) {
            $site = Site::find($site);

            $this->site = $this->parseSiteFromModel($site);
        } else {
            $this->site = $this->getCurrentSiteInfo();
        }

        if (empty($this->site)) {
            abort(404, 'Site not found.');
        }

        if ($this->site->status == Site::STATUS_BANNED) {
            abort(403, 'Site has been banned.');
        }

        $this->site = $this->siteSetup->setup($this->site);
    }

    protected function initConsole(): void
    {
        $argv = $_SERVER['argv'];
        if (($argv[0] ?? '') == 'artisan' && ($argv[1] ?? '') == 'network:run') {
            $siteID = (int) $argv[3];
            $site = $this->db->table('network_sites')
                ->where(['id' => $siteID])
                ->first();

            if (!$site) {
                throw new \RuntimeException("Can not find site {$siteID}");
            }

            $this->site = $site;

            $this->siteSetup->setup($site);

            $baseDomain = $this->config->get('network.domain');

            $host = parse_url($this->config->get('app.url'));

            $this->config->set('app.url', "{$host['scheme']}://{$site->domain}.{$baseDomain}");

            $this->forceRootUrl("{$host['scheme']}://{$site->domain}.{$baseDomain}");
        } else {
            $this->site = $this->getRootSite();
        }
    }

    protected function forceRootUrl(string $url): void
    {
        $this->url->forceRootUrl($url);
    }

    protected function getCurrentSiteInfo(): ?object
    {
        if (is_admin_page()) {
            $siteId = $this->request->segment(2);

            if (uuid_is_valid($siteId)) {
                return $this->db->table('network_sites')
                    ->where('uuid', '=', $siteId)
                    ->first();
            }
        }

        $domain = $this->getCurrentDomain();

        return $this->cache->rememberForever(
            md5($domain),
            function () use ($domain) {
                if ($domain == $this->config->get('network.domain')) {
                    return $this->getRootSite();
                }

                $subdomain = str_replace("." . $this->config->get('network.domain'), "", $domain);

                return $this->db->table('network_sites')
                    ->where(
                        function ($q) use ($domain, $subdomain) {
                            $q->where('subdomain', '=', $subdomain);
                            $q->orWhereExists(
                                function ($q2) use ($domain) {
                                    $q2->select(['id']);
                                    $q2->from('network_domain_mappings');
                                    $q2->whereColumn('network_domain_mappings.site_id', '=', 'network_sites.id');
                                    $q2->where('domain', '=', $domain);
                                }
                            );
                        }
                    )
                    ->first();
            }
        );
    }

    protected function getRootSite(): object
    {
        return (object) [
            'id' => 0,
            'db_id' => null,
            'status' => Site::STATUS_ACTIVE,
            'root_connection' => $this->getRootConnection(),
        ];
    }

    protected function isRootDomain(string $domain): bool
    {
        return $domain == $this->config->get('network.domain');
    }

    protected function parseSiteFromModel(Site $site): object
    {
        $site = (object) $site->toArray();
        $site->root_connection = $this->getRootConnection();
        return $site;
    }
}
