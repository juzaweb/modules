<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Actions;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Facades\HookAction;

class EnqueueStyleAction extends Action
{
    public function handle(): void
    {
        $this->addAction(self::BACKEND_HEADER_BLADE_ACTION, [$this, 'enqueueStylesHeader']);
        $this->addAction(self::BACKEND_FOOTER_BLADE_ACTION, [$this, 'enqueueStylesFooter']);
    }

    public function enqueueStylesHeader(): void
    {
        $scripts = HookAction::getEnqueueScripts();
        $styles = HookAction::getEnqueueStyles();

        echo view(
            'cms::frontend.styles',
            ['scripts' => $scripts, 'styles' => $styles]
        )->render();
    }

    public function enqueueStylesFooter(): void
    {
        $scripts = HookAction::getEnqueueScripts(true);
        $styles = HookAction::getEnqueueStyles(true);

        echo view(
            'cms::frontend.styles',
            ['scripts' => $scripts, 'styles' => $styles]
        )->render();
    }
}
