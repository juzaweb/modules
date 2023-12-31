<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Backend\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Juzaweb\CMS\Contracts\HookActionContract;

class SettingRequest extends FormRequest
{
    public function __construct(
        protected HookActionContract $hookAction
    ) {
        parent::__construct();
    }

    public function rules(): array
    {
        $configs = $this->hookAction->getConfigs()->only(array_keys($this->input()));

        $checkboxs = $configs
            ->where('type', 'checkbox')
            ->keys()
            ->toArray();

        return $configs->map(
            function ($item) use ($checkboxs) {
                if ($validators = Arr::get($item, 'validators')) {
                    return $validators;
                }

                $rule = ['nullable'];
                if (in_array($item['name'], $checkboxs)) {
                    $rule[] = 'in:1';
                }

                return $rule;
            }
        )->toArray();
    }

    protected function prepareForValidation(): void
    {
        $checkboxs = $this->hookAction->getConfigs()
            ->where('type', 'checkbox')
            ->whereIn('name', array_keys($this->input()))
            ->keys()
            ->toArray();
        $input = [];
        foreach ($checkboxs as $checkbox) {
            if (!$this->has($checkbox)) {
                $input[$checkbox] = 0;
            }
        }

        $this->merge($input);
    }
}
