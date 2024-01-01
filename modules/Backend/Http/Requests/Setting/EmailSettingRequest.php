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

class EmailSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email.host' => ['required'],
            'email.port' => ['required'],
            'email.encryption' => ['nullable', 'in:ssl,tls'],
            'email.username' => ['required'],
            'email.password' => ['required'],
            'email.from_address' => ['required','email'],
            'email.from_name' => ['required', 'string', 'max:100'],
        ];
    }
}
