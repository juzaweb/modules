<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class MediaSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'theme' => ['required', 'array'],
            'config' => ['required', 'array'],
            'theme.thumbnail_sizes.*.width' => ['nullable', 'integer'],
            'theme.thumbnail_sizes.*.height' => ['nullable', 'integer'],
            'config.auto_resize_thumbnail.*' => ['nullable', 'integer', 'in:1'],
            'config.thumbnail_defaults.*' => ['nullable', 'string'],
        ];
    }
}
