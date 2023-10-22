<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Commands\SEO;

use Illuminate\Console\Command;
use Juzaweb\Backend\Models\Post;

class AutoSubmitUrlBing extends Command
{
    protected $name = 'cms:auto-submit-url-bing';

    protected $description = 'Auto submit url for SEO.';

    public function handle(): void
    {
        if (!get_config('jw_auto_submit_url_bing')) {
            return;
        }

        $apiKey = $this->getBingKey();
        if (empty($apiKey)) {
            return;
        }

        $lastDate = get_config('jw_last_bing_submit_date', '2000-01-01 00:00:00');

        $links = Post::wherePublish()
            ->where('updated_at', '>', $lastDate)
            ->orderBy('updated_at', 'asc')
            ->limit(100)
            ->get();

        if ($links->isEmpty()) {
            return;
        }

        $urls = $links->map(
            function ($item) {
                return [
                    'url' => $item->getLink()
                ];
            }
        )
            ->pluck('url')
            ->toArray();

        $url = config('app.url');

        $response = $this->client()->post(
            'https://ssl.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey=' . $apiKey,
            [
                'headers' => [
                    'Accept' => "application/json",
                    'Content-Type' => "application/json"
                ],
                'json' => [
                    'siteUrl' => $url,
                    'urlList' => $urls
                ]
            ]
        );

        if ($response->getStatusCode() == 200) {
            $this->info('Submit urls to Bing successfull.');

            set_config('jw_last_bing_submit_date', $links->last()->updated_at->format('Y-m-d H:i:s'));
        }
    }

    protected function getBingKey(): string
    {
        return get_config('jw_bing_api_key');
    }
}
