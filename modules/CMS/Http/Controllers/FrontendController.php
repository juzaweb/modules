<?php

namespace Juzaweb\CMS\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;
use Inertia\Response as InertiaResponse;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\CMS\Facades\Theme;
use Juzaweb\CMS\Traits\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;

class FrontendController extends Controller
{
    use ResponseMessage;

    protected string $template;

    public function callAction($method, $parameters): mixed
    {
        $this->template = Theme::currentTheme()->getTemplate();

        /**
         * Action after call action frontend
         * Add action to this hook add_action('frontend.call_action', $callback)
         */
        do_action(Action::FRONTEND_CALL_ACTION, $method, $parameters);

        do_action(Action::WIDGETS_INIT);

        do_action(Action::BLOCKS_INIT);

        return parent::callAction($method, $parameters);
    }

    protected function getPermalinks($base = null)
    {
        if ($base) {
            return collect(HookAction::getPermalinks())
                ->where('base', $base)
                ->first();
        }

        return collect(HookAction::getPermalinks());
    }

    protected function view($view, $params = []): Factory|ViewContract|string|InertiaResponse
    {
        return Theme::render($view, $params);
    }
}
