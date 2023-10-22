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

use Exception;
use Google\Client;
use Google\Service\Indexing;
use Illuminate\Console\Command;
use Juzaweb\Backend\Models\Post;

class AutoSubmitUrlGoogle extends Command
{
    protected $name = 'cms:auto-submit-url-google';

    protected $description = 'Auto submit url for SEO.';

    public function handle(): void
    {
        if (!get_config('jw_auto_submit_url_google')) {
            return;
        }

        try {
            for ($i = 1; $i <= 2; $i++) {
                $this->submitUrlGoogle();

                sleep(5);
            }
        } catch (Exception $e) {
            report($e);
            $this->error($e->getMessage());
        }
    }

    protected function submitUrlGoogle(): void
    {
        $lastDate = get_config('jw_last_google_submit_date', '2000-01-01 00:00:00');

        $links = Post::wherePublish()
            ->where('updated_at', '>', $lastDate)
            ->orderBy('updated_at', 'asc')
            ->limit(100)
            ->get();

        if ($links->isEmpty()) {
            return;
        }

        $lastUpdatedAt = $links->last()->updated_at->format('Y-m-d H:i:s');

        $client = new Client();
        $client->setAuthConfig(storage_path('services/service_account.json'));
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $client->setUseBatch(true);

        $service = new Indexing($client);
        $batch = $service->createBatch();

        foreach ($links as $link) {
            $this->info("=> Submiting url: ". $link->getLink(true));
            $url = new Indexing\UrlNotification();
            $url->setUrl($link->getLink(true));
            $url->setType('URL_UPDATED');
            $batch->add($service->urlNotifications->publish($url));
        }

        $batch->execute();

        set_config('jw_last_google_submit_date', $lastUpdatedAt);

        $this->info('Submit urls to Google successfull.');
    }
}
