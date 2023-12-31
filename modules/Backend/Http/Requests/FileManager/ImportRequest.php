<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Backend\Http\Requests\FileManager;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'url',
                'max:150',
            ],
            'working_dir' => [
                'nullable',
                'numeric'
            ],
            'disk' => [
                'nullable',
                'in:public,protected,tmp',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'disk.in' => trans('cms::message.invalid_disk'),
        ];
    }
}
