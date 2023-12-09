<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Http\Requests\Theme;

use Illuminate\Foundation\Http\FormRequest;

class ActivateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'theme' => ['required', 'string'],
        ];
    }
}
