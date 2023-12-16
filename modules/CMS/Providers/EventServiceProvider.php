<?php

namespace Juzaweb\CMS\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Juzaweb\Backend\Events\AfterPluginBulkAction;
use Juzaweb\Backend\Events\AfterPostSave;
use Juzaweb\Backend\Events\DumpAutoloadPlugin;
use Juzaweb\Backend\Events\PostViewed;
use Juzaweb\Backend\Events\Users\RegisterSuccessful;
use Juzaweb\Backend\Listeners\CountViewPost;
use Juzaweb\Backend\Listeners\DeleteRequirePluginsMessageListener;
use Juzaweb\Backend\Listeners\DumpAutoloadPluginListener;
use Juzaweb\Backend\Listeners\ResizeThumbnailPostListener;
use Juzaweb\Backend\Listeners\SaveSeoMetaPost;
use Juzaweb\Backend\Listeners\SendMailRegisterSuccessful;
use Juzaweb\CMS\Events\EmailHook;
use Juzaweb\CMS\Listeners\SendEmailHook;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EmailHook::class => [
            SendEmailHook::class,
        ],
        RegisterSuccessful::class => [
            SendMailRegisterSuccessful::class,
        ],
        PostViewed::class => [
            CountViewPost::class
        ],
        DumpAutoloadPlugin::class => [
            DumpAutoloadPluginListener::class,
        ],
        AfterPostSave::class => [
            SaveSeoMetaPost::class,
            ResizeThumbnailPostListener::class,
        ],
        AfterPluginBulkAction::class => [
            DeleteRequirePluginsMessageListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        //
    }
}
