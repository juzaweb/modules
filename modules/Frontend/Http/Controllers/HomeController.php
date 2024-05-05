<?php

namespace Juzaweb\Frontend\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Juzaweb\Backend\Repositories\PostRepository;
use Juzaweb\CMS\Http\Controllers\FrontendController;
use Juzaweb\CMS\Models\Model;

class HomeController extends FrontendController
{
    public function __construct(protected PostRepository $postRepository)
    {
    }

    public function index(Request $request)
    {
        do_action('theme.home.index');

        if ($pageId = jw_home_page()) {
            $page = $this->postRepository->frontendFind($pageId);

            if ($page) {
                return App::call(PageController::class .'@detail', ['id' => $page]);
            }
        }

        return $this->handlePage($request);
    }

    protected function handlePage(Request $request)
    {
        $params = $this->getParamsForTemplate();

        return apply_filters('theme.page.home.handle', $this->view($this->getViewPage(), $params), $params);
    }

    protected function getParamsForTemplate(): array
    {
        $params = get_configs(['title', 'description', 'banner']);

        $postType = get_config('home_post_type');

        $params['page'] = $this->postRepository
            ->scopeQuery(fn(Builder|Model $q) => $q->when($postType, fn($q2) => $q2->where(['type' => 'posts'])))
            ->frontendListPaginate(get_config('posts_per_page', 12));

        return $params;
    }

    protected function getViewPage(): string
    {
        return 'theme::index';
    }
}
