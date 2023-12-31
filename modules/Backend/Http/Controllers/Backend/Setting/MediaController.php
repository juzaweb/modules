<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Backend\Http\Controllers\Backend\Setting;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Juzaweb\Backend\Http\Requests\Setting\MediaSettingRequest;
use Juzaweb\CMS\Contracts\HookActionContract as HookAction;
use Juzaweb\CMS\Http\Controllers\BackendController;

class MediaController extends BackendController
{
    public function __construct(protected HookAction $hookAction)
    {
    }

    public function index(): View
    {
        $title = trans('cms::app.media_setting.title');
        $postTypes = $this->hookAction->getPostTypes();
        $thumbnailDefaults = get_config('thumbnail_defaults', []);
        $thumbnailSizes = $this->hookAction->getThumbnailSizes()->toArray();

        return view(
            'cms::backend.setting.media',
            compact(
                'title',
                'postTypes',
                'thumbnailDefaults',
                'thumbnailSizes'
            )
        );
    }

    public function save(MediaSettingRequest $request): JsonResponse|RedirectResponse
    {
        $configs = Arr::only($request->post('config', []), ['thumbnail_defaults', 'auto_resize_thumbnail']);
        $themeConfigs = Arr::only($request->post('theme', []), ['thumbnail_sizes']);

        foreach ($configs as $name => $value) {
            set_config($name, $value);
        }

        foreach ($themeConfigs as $name => $value) {
            set_theme_config($name, $value);
        }

        return $this->success(
            trans('cms::app.updated_successfully')
        );
    }
}
