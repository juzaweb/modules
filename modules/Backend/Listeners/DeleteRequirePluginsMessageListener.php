<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Listeners;

use Juzaweb\Backend\Events\AfterPluginBulkAction;
use Juzaweb\CMS\Contracts\BackendMessageContract;
use Juzaweb\CMS\Contracts\LocalPluginRepositoryContract;
use Juzaweb\CMS\Contracts\LocalThemeRepositoryContract;
use Juzaweb\CMS\Interfaces\Theme\PluginInterface;

class DeleteRequirePluginsMessageListener
{
    public function __construct(
        protected BackendMessageContract $message,
        protected LocalPluginRepositoryContract $plugins,
        protected LocalThemeRepositoryContract $themes
    ) {
    }

    public function handle(AfterPluginBulkAction $event): void
    {
        if ($event->action !== 'activate') {
            return;
        }

        $theme = $this->themes->currentTheme();
        $requires = $theme->getPluginRequires();

        $plugins = collect($this->plugins->all())->filter(
            fn (PluginInterface $plugin) => array_key_exists($plugin->getName(), $requires) && $plugin->isDisabled()
        );

        if ($plugins->isEmpty()) {
            $this->message->deleteGroup('require_plugins');
        }
    }
}
