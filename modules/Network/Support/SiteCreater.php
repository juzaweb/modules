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

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Juzaweb\CMS\Facades\Config as DbConfig;
use Juzaweb\CMS\Models\User;
use Juzaweb\Network\Contracts\NetworkRegistionContract;
use Juzaweb\Network\Contracts\SiteCreaterContract;
use Juzaweb\Network\Models\Site;

class SiteCreater implements SiteCreaterContract
{
    protected ConnectionResolverInterface $db;

    protected ConfigRepository $config;

    protected NetworkRegistionContract $networkRegistion;

    public function __construct(
        ConnectionResolverInterface $db,
        ConfigRepository $config,
        NetworkRegistionContract $networkRegistion
    ) {
        $this->db = $db;
        $this->config = $config;
        $this->networkRegistion = $networkRegistion;
    }

    public function create(string $subdomain, array $args = [], ?User $user = null): Site
    {
        if (Site::where('subdomain', '=', $subdomain)->exists()) {
            throw new \RuntimeException("Site {$subdomain} already exist.");
        }

        $data = array_merge($this->parseDataSite($args), ['subdomain' => $subdomain]);
        $data['created_by'] = ($user ?? Auth::user())->id;

        $site = Site::create($data);

        $this->setupSite($site, $args, $user);

        return $site;
    }

    public function setupSite(Site $site, array $args = [], ?User $user = null): void
    {
        $this->networkRegistion->init($site->id);

        $user = ($user ?? Auth::user())->replicate();
        $user->setTable('subsite_users');
        $user->setAttribute('status', User::STATUS_ACTIVE);
        $user->setAttribute('is_admin', true);
        $user->setAttribute('site_id', $site->id);
        $user->setAttribute('remember_token', null);
        $user->save();

        $this->makeDefaultConfigs($args);
    }

    protected function makeDefaultConfigs(array $args): void
    {
        DbConfig::setConfig('title', Arr::get($args, 'title', 'Juzaweb CMS - Laravel CMS for Your Project'));
        DbConfig::setConfig(
            'description',
            Arr::get(
                $args,
                'description',
                'Juzacms is a Content Management System (CMS)'
                .' and web platform whose sole purpose is to make your development workflow simple again.'
            )
        );
        DbConfig::setConfig('author_name', 'Juzaweb Team');
        DbConfig::setConfig('user_registration', 1);
        DbConfig::setConfig('user_verification', 0);
    }

    protected function parseDataSite(array $args): array
    {
        $defaults = ['status' => Site::STATUS_ACTIVE];

        return array_merge($defaults, $args);
    }
}
