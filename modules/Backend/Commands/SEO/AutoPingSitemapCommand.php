<?php

namespace Juzaweb\Backend\Commands\SEO;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Juzaweb\Backend\Models\Post;

class AutoPingSitemapCommand extends Command
{
    protected $signature = 'cms:auto-ping';

    protected $description = 'Auto ping sitemap and submit url for SEO.';

    public function handle(): int
    {
        $pingCount = get_config('sitemap_last_ping_count', 0);

        if ($pingCount >= Post::selectFrontendBuilder()->count()) {
            $this->warn('Sitemap no updates.');
            return self::SUCCESS;
        }

        if (get_config('jw_auto_ping_google_sitemap')) {
            $this->info('Ping sitemap to google.');

            try {
                $this->pingSiteMapGoogle();
            } catch (\Exception $e) {
                report($e);
                $this->error($e->getMessage());
            }
        }

        if (get_config('jw_auto_ping_bing_sitemap')) {
            $this->info('Ping sitemap to bing.');

            try {
                $this->pingSiteMapBing();
            } catch (\Exception $e) {
                report($e);
                $this->error($e->getMessage());
            }
        }

        return self::SUCCESS;
    }

    protected function pingSiteMapBing(): void
    {
        $sitemap = 'https://www.bing.com/ping?sitemap=' . url('sitemap.xml');

        $response = $this->client()->get($sitemap);

        if ($response->getStatusCode() == 200) {
            $this->info('Ping site map successfull.');
        }
    }

    protected function pingSiteMapGoogle(): void
    {
        $sitemap = 'https://www.google.com/ping?sitemap=' . url('sitemap.xml');

        $response = $this->client()->get($sitemap);

        if ($response->getStatusCode() == 200) {
            $this->info('Ping site map successfull.');
        }
    }

    protected function client(): Client
    {
        return new Client(['timeout' => 10, 'connect_timeout' => 10]);
    }
}
