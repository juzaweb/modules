<?php

namespace Juzaweb\Backend\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\CMS\Support\MenuCollection;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'cms::layouts.backend-inertia';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(
            parent::share($request),
            $this->getShareDate($request)
        );
    }

    protected function getShareDate(Request $request): array
    {
        $user = $request->user();
        $userData = Arr::only($user->toArray(), ['id', 'name', 'email']);
        $userData['avatar'] = $user->getAvatar();
        $langs = Cache::remember(
            'top_menu_languages',
            3600,
            function () {
                return cms_languages()->values();
            }
        );

        $leftMenuItems = MenuCollection::make(apply_filters('get_admin_menu', HookAction::getAdminMenu()));
        $currentLang = $user->language ?? get_config('language', 'en');
        $trans = [
            'cms' => [
                'app' => array_merge(trans('cms::app', [], 'en'), trans('cms::app')),
                'message' => array_merge(trans('cms::message', [], 'en'), trans('cms::message')),
            ]
        ];

        return [
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
            'currentTheme' => current_theme(),
            'user' => $userData,
            'langs' => $langs,
            'currentLang' => $currentLang,
            'trans' => $trans,
            'adminUrl' => admin_url(),
            'adminPrefix' => config('juzaweb.admin_prefix'),
            'totalNotifications' => count_unread_notifications(),
            'leftMenuItems' => $leftMenuItems,
            'currentUrl' => $request->url(),
            'currentPath' => $request->path(),
            'config' => [
                'title' => get_config('title'),
                'description' => get_config('description'),
            ]
        ];
    }
}
