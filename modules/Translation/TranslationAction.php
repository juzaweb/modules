<?php

namespace Juzaweb\Translation;

use Juzaweb\CMS\Abstracts\Action;

class TranslationAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::BACKEND_INIT, [$this, 'addBackendMenus']);
    }

    public function addBackendMenus(): void
    {
        if (config('network.enable')) {
            return;
        }

        $this->registerAdminPage(
            'translations',
            [
                'title' => trans('cms::app.translations'),
                'menu' => [
                    'icon' => 'fa fa-language',
                    'position' => 90,
                    'parent' => 'tools',
                ],
            ]
        );
    }
}
