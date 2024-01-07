<?php

namespace Juzaweb\Frontend\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Response;
use Juzaweb\Backend\Http\Resources\UserResource;
use Juzaweb\Backend\Repositories\UserRepository;
use Juzaweb\CMS\Contracts\HookActionContract;
use Juzaweb\CMS\Http\Controllers\FrontendController;
use Juzaweb\Frontend\Http\Requests\ChangePasswordRequest;
use Juzaweb\Frontend\Http\Requests\UpdateProfileRequest;

class ProfileController extends FrontendController
{
    public function __construct(
        protected UserRepository $userRepository,
        protected HookActionContract $hookAction
    ) {
    }

    public function index(?string $slug = null)
    {
        $pages = $this->hookAction->getProfilePages()->toArray();
        $page = Arr::get($pages, $slug ?? 'index');

        abort_unless($page, 404);

        $title = $page['title'];
        if ($callback = Arr::get($page, 'callback')) {
            return app()->call("{$callback[0]}@{$callback[1]}", ['page' => $page]);
        }

        $params = compact(
            'title',
            'slug',
        );

        $params['pages'] = $this->filterPagesParams($pages);
        $params['page'] = $this->filterPageParam($page);

        $params = apply_filters(
            'theme.profile.params',
            array_merge($params, $this->arrayMapData(Arr::get($page, 'data', []))),
            $page,
            $slug,
            $pages
        );

        return $this->view($this->getViewForPage($page), $params);
    }

    public function update(UpdateProfileRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        DB::transaction(
            function () use ($user, $request) {
                $update = $request->only(['name']);

                if ($password = $request->input('password')) {
                    $update['password'] = Hash::make($password);
                }

                $this->userRepository->update($update, $user->id);

                do_action('theme.profile.update', $user, $request->all());
            }
        );

        return $this->success(
            [
                'message' => trans('cms::message.update_successfully'),
            ]
        );
    }

    public function changePassword(Request $request): View|Factory|Response|string
    {
        $title = trans('cms::app.change_password');
        $user = UserResource::make($request->user())->toArray($request);

        return $this->view(
            'theme::profile.change_password',
            compact(
                'title',
                'user'
            )
        );
    }

    public function notification(): View|Factory|Response|string
    {
        global $jw_user;

        $title = trans('cms::app.profile');

        $user = $jw_user;

        $notifications = $user->notifications->toArray();

        return $this->view(
            'theme::profile.notification.index',
            compact(
                'title',
                'notifications',
                'user'
            )
        );
    }

    public function doChangePassword(ChangePasswordRequest $request): JsonResponse|RedirectResponse
    {
        $currentPassword = $request->post('current_password');
        $password = $request->post('password');
        $user = $request->user();

        if (!Hash::check($currentPassword, $user->password)) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => trans('cms::app.current_password_incorrect'),
                ]
            );
        }

        DB::transaction(fn() => $user->update(['password' => Hash::make($password)]));

        return $this->success(
            [
                'message' => trans('cms::app.change_password_successfully'),
            ]
        );
    }

    protected function filterPageParam(array $page): array
    {
        unset($page['data']);

        return $page;
    }

    protected function filterPagesParams(array $pages): array
    {
        return collect($pages)->map(function ($page) {
            unset($page['data']);
            $page['active'] = $page['slug'] == 'index'
                ? request()?->is('profile')
                : request()?->is("profile/{$page['slug']}") || request()?->is("profile/{$page['slug']}/*");
            return $page;
        })->toArray();
    }

    protected function arrayMapData(array $data): array
    {
        return collect($data)
            ->map(
                function ($item) {
                    if ($item instanceof \Closure) {
                        return $item();
                    }

                    return $item;
                }
            )
            ->toArray();
    }

    protected function getViewForPage(array $page)
    {
        $slugName = Str::slug(Arr::get($page, 'slug'), '_');
        $viewName = 'theme::profile.index';

        if ($slugName !== 'index') {
            if (theme_view_exists("theme::profile.{$slugName}")) {
                $viewName = "theme::profile.{$slugName}";
            }

            if (theme_view_exists("theme::profile.{$slugName}.index")) {
                $viewName = "theme::profile.{$slugName}.index";
            }
        }

        return apply_filters('theme.profile.view', $viewName, $page);
    }
}
