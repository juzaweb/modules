<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Actions;

use Juzaweb\CMS\Abstracts\Action;

class ConfigAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::NETWORK_INIT, [$this, 'registerConfigs']);
    }

    public function registerConfigs(): void
    {
        $this->hookAction->registerNetworkSettingPage(
            'system',
            [
                'label' => trans('cms::app.setting'),
                'menu' => [
                    'icon' => 'fa fa-cogs',
                    'position' => 99,
                ]
            ]
        );

        $this->hookAction->addNetworkSettingForm(
            'general',
            [
                'name' => trans('cms::app.general_setting'),
            ]
        );

        $this->hookAction->registerNetworkConfig(
            [
                'network_domain' => [
                    'label' => trans('cms::app.network.network_domain'),
                    'type' => 'text',
                    'form' => 'general',
                    'data' => [
                        'default' => config('network.domain'),
                        'disabled' => true,
                    ]
                ]
            ]
        );
    }
}
