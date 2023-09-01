<?php

namespace Juzaweb\Backend\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use Juzaweb\CMS\Support\Manager\TranslationManager;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'cms::layouts.backend-inertia';

    public function __construct(protected TranslationManager $translationManager)
    {
    }

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
                return $this->translationManager
                    ->locale('cms')
                    ->languages()
                    ->values();
            }
        );

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
            'current_theme' => current_theme(),
            'user' => $userData,
            'langs' => $langs,
            'currentLang' => $currentLang,
            'trans' => $trans,
            'admin_url' => admin_url(),
            'admin_prefix' => config('juzaweb.admin_prefix'),
            'total_notifications' => count_unread_notifications(),
        ];
    }
}
