<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Network\Actions;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\Network\Facades\Network;

class NetworkAction extends Action
{
    public function handle(): void
    {
        if (Network::isRootSite()) {
            $this->addAction('backend.menu_top', [$this, 'addMenuAdmin']);

            $this->addAction(
                Action::NETWORK_INIT,
                [$this, 'registerMasterAdminMenu']
            );
        }
    }

    public function addMenuAdmin(): void
    {
        echo e(view('network::components.menu_admin'));
    }

    public function registerMasterAdminMenu(): void
    {
        $this->hookAction->addMasterAdminMenu(
            trans('cms::app.dashboard'),
            'dashboard',
            [
                'icon' => 'fa fa-dashboard',
                'position' => 1,
            ]
        );

        $this->hookAction->addMasterAdminMenu(
            trans('cms::app.network.sites'),
            'sites',
            [
                'icon' => 'fa fa-globe',
                'position' => 10,
            ]
        );

        $this->hookAction->addMasterAdminMenu(
            trans('cms::app.themes'),
            'themes',
            [
                'icon' => 'fa fa-paint-brush',
                'position' => 40,
            ]
        );

        $this->hookAction->addMasterAdminMenu(
            trans('cms::app.plugins'),
            'plugins',
            [
                'icon' => 'fa fa-plug',
                'position' => 45,
            ]
        );

        $this->hookAction->addAdminMenu(
            'Log Viewer',
            'log-viewer',
            [
                'parent' => 'tools',
                'icon' => 'fa fa-history',
                'position' => 99,
                'turbolinks' => false,
            ]
        );

        $this->hookAction->addAdminMenu(
            trans('cms::app.email_logs'),
            'logs.email',
            [
                'icon' => 'fa fa-cogs',
                'position' => 51,
                'parent' => 'managements',
            ]
        );

        $this->hookAction->addMasterAdminMenu(
            trans('cms::app.setting'),
            'setting',
            [
                'icon' => 'fa fa-cogs',
                'position' => 99,
            ]
        );
    }
}
