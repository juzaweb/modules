<?php

namespace Juzaweb\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Juzaweb\Backend\Events\PostViewed;
use Juzaweb\Backend\Models\Post;
use Juzaweb\Backend\Repositories\PostRepository;
use Juzaweb\CMS\Contracts\HookActionContract;
use Juzaweb\CMS\Facades\ThemeLoader;
use Juzaweb\CMS\Http\Controllers\FrontendController;

class PageController extends FrontendController
{
    protected array $themeRegister;

    public function __construct(
        protected PostRepository $postRepository,
        protected HookActionContract $hookAction
    ) {
    }

    public function index(Request $request, ...$slug)
    {
        $pageSlug = $this->getPageSlug($slug);

        /**
         * @var Post $page
         */
        $page = $this->postRepository->frontendFindBySlug($pageSlug);

        return $this->handlePage($request, $page, $slug);
    }

    public function detail(Request $request, $id)
    {
        if (!$id instanceof Post) {
            /**
             * @var Post $page
             */
            $page = $this->postRepository->frontendFind($page);

            return $this->handlePage($request, $page);
        }

        return $this->handlePage($request, $id);
    }

    protected function getPageSlug($slug)
    {
        return apply_filters('theme.page_slug', $slug[0], $slug);
    }

    protected function handlePage(Request $request, Post $page, array $slug = [])
    {
        // Redirect home page if page is home
        if ($this->isHomePage($page)) {
            return redirect()->route('home', [], 301);
        }

        $theme = jw_theme_info();
        $params = $this->getPageParams($page, $slug, $request);

        $params = apply_filters('theme.get_params_page', $params, $page);
        $view = $this->getViewPage($page, $theme, $params);

        if (!is_bot_request()) {
            event(new PostViewed($page));
        }

        /* Add pages filter */
        $result = apply_filters(
            'theme.page.handle',
            $this->view($view, $params),
            $page,
            $slug,
            $params
        );

        /* Add single page filter */
        return apply_filters(
            "theme.page_{$page->id}.handle",
            $result,
            $page,
            $slug,
            $params
        );
    }

    protected function isHomePage(Post $page): bool
    {
        return get_config('show_on_front')
            && $page->id == get_config('home_page')
            && Route::getCurrentRoute()?->getName() !== 'home';
    }

    /**
     * @param  Post  $page
     * @param  array  $slug
     * @param Request $request
     * @return array
     */
    protected function getPageParams(Post $page, array $slug, Request $request): array
    {
        $image = $page->thumbnail ? upload_url($page->thumbnail) : null;

        if (is_home()) {
            $config = get_configs(['title', 'description']);

            $params = [
                'post' => $page,
                'title' => $config['title'],
                'description' => $config['description'],
                'slug' => $slug,
                'image' => $image,
            ];
        } else {
            $params = [
                'post' => $page,
                'title' => $page->title,
                'description' => $page->description,
                'slug' => $slug,
                'image' => $image,
            ];
        }

        if (($template = $page->getMeta('template'))
            && $data = $this->getThemeRegister("templates.{$template}.data")
        ) {
            foreach ($data as $key => $option) {
                $params[$key] = $this->getPageCustomData($request, $option, $params);
            }
        }

        $data = apply_filters(
            "frontend.post_type.detail.data",
            $params,
            $page
        );

        return apply_filters(
            "frontend.post_type.pages.detail.data",
            $data,
            $page
        );
    }

    protected function getPageCustomData(Request $request, array $option, array $params)
    {
        $type = Arr::get($option, 'type');
        if (empty($type)) {
            return null;
        }

        $pageData = $this->hookAction->getPageCustomDatas($type);

        if ($pageData) {
            return apply_filters(
                'theme.get_page_custom_data',
                $pageData->get('callback')($request, $option, $params),
                $option,
                $params
            );
        }

        $data = match ($type) {
            'post_liked' => $this->postRepository
                ->scopeQuery(
                    fn ($query) => $query
                        ->when(isset($option['post_type']), fn ($q) => $q->where('type', '=', $option['post_type']))
                )
                ->getLikedPosts($request->user(), get_config('posts_per_page', 12))
                ->appends(request()?->query()),
            'popular_posts' => get_popular_posts(
                Arr::get($option, 'post_type'),
                $params['post'],
                Arr::get($option, 'limit', 5)
            ),
            'related_posts' => get_related_posts(
                $params['post'],
                $option['limit'] ?? 5,
                $option['taxonomy'] ?? null
            ),
            'previous_post' => get_previous_post($params['post']),
            'next_post' => get_next_post($params['post']),
            default => null,
        };

        return apply_filters('theme.get_page_custom_data', $data, $option, $params);
    }

    protected function getViewPage(Post $page, $themeInfo, array $params = []): string
    {
        /* Get view by template */
        if ($template = $page->getMeta('template')) {
            $templates = ThemeLoader::getTemplates($themeInfo->get('name'), $template);
            $templateView = Arr::get($templates, 'view', "theme::templates.{$template}");

            if ($templateView && theme_view_exists($templateView)) {
                $view = $templateView;
            }
        }

        /* Get view default of theme */
        if (empty($view)) {
            $template = get_name_template_part('page', 'single');
            $view = "theme::template-parts.{$template}";

            if (!theme_view_exists($view)) {
                $view = 'theme::template-parts.single';
            }
        }

        return apply_filters('theme.get_view_page', $view, $page, $params);
    }

    protected function getThemeRegister(string $key = null): ?array
    {
        if (!isset($this->themeRegister)) {
            $this->themeRegister = ThemeLoader::getRegister(jw_current_theme());
        }

        if ($key) {
            return Arr::get($this->themeRegister, $key);
        }

        return $this->themeRegister;
    }
}
