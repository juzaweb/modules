<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Juzaweb\Backend\Http\Requests\Setting\SettingRequest;
use Juzaweb\CMS\Contracts\GlobalDataContract;
use Juzaweb\CMS\Contracts\HookActionContract;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\Network\Contracts\NetworkConfig;

class SettingController extends BackendController
{
    public function __construct(
        protected GlobalDataContract $globalData,
        protected HookActionContract $hookAction,
        protected NetworkConfig $networkConfig
    ) {
    }

    public function index($page, $form = 'general'): View
    {
        $forms = $this->getForms($page);
        if (!isset($forms[$form])) {
            $form = $forms->first()?->get('key');
        }

        abort_unless($form, 404);

        $configs = $this->hookAction->getNetworkConfigs()->where('form', $form);
        $title = $forms[$form]['name'] ?? trans('cms::app.system_setting');

        return view(
            'cms::backend.setting.system.index',
            [
                'title' => $title,
                'component' => $form,
                'forms' => $forms,
                'configs' => $configs,
                'page' => $page,
            ]
        );
    }

    public function save(SettingRequest $request): JsonResponse|RedirectResponse
    {
        $configs = $request->only($this->hookAction->getNetworkConfigs()->keys()->toArray());

        foreach ($configs as $key => $config) {
            if (!$request->has($key)) {
                continue;
            }

            if (is_array($config) && !is_numeric(array_key_first($config))) {
                $config = array_replace_recursive(get_config($key, []), $config);
            }

            $this->networkConfig->setConfig($key, $config);
        }

        return $this->success(
            [
                'message' => trans('cms::app.saved_successfully'),
            ]
        );
    }

    protected function getForms(string $page): Collection
    {
        return collect($this->globalData->get('network_setting_forms'))
            ->where('page', $page)
            ->sortBy('priority');
    }
}
