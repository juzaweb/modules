<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Http\Requests\Appearance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Juzaweb\CMS\Contracts\HookActionContract;

class SettingRequest extends FormRequest
{
    public function __construct(protected HookActionContract $hookAction)
    {
        parent::__construct();
    }

    public function rules(): array
    {
        $configs = $this->hookAction->getConfigs()
            ->only($this->collect('config')->keys());

        $rules = [
            'theme' => ['required', 'array'],
            'config' => ['required', 'array'],
        ];

        $themeConfigs = $this->hookAction->getThemeSettings()
            ->only($this->collect('theme')->keys());

        foreach ($configs as $config) {
            if ($validators = Arr::get($config, 'validators')) {
                $rules['config.' . $config['name']] = $validators;
            } else {
                $rules['config.' . $config['name']] = ['nullable'];
            }
        }

        foreach ($themeConfigs as $config) {
            if ($validators = Arr::get($config, 'validators')) {
                $rules['theme.' . $config['name']] = $validators;
            } else {
                $rules['theme.' . $config['name']] = ['nullable'];
            }
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $configs = $this->hookAction->getConfigs()
            ->only($this->collect('config')->keys())
            ->keys()
            ->toArray();

        $themeConfigs = $this->hookAction->getThemeSettings()
            ->only($this->collect('theme')->keys())
            ->keys()
            ->toArray();

        $this->merge([
            'theme' => Arr::only($this->input('theme', []), $themeConfigs),
            'config' => Arr::only($this->input('config', []), $configs),
        ]);
    }
}
