<?php

namespace Juzaweb\Backend\Http\Controllers\Backend\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Juzaweb\Backend\Http\Datatables\UserDataTable;
use Juzaweb\CMS\Abstracts\DataTable;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Traits\ResourceController;

class UserController extends BackendController
{
    use ResourceController {
        getDataForForm as DataForForm;
    }

    protected string $viewPrefix = 'cms::backend.user';

    /**
     * Validator for store and update
     *
     * @param  array  $attributes
     * @param  mixed  ...$params
     * @return array
     */
    protected function validator(array $attributes, ...$params): array
    {
        $allStatus = array_keys(User::getAllStatus());

        return [
            'name' => 'required|string|max:250',
            'password' => [
                Rule::requiredIf(
                    function () use ($attributes) {
                        return empty($attributes['id']);
                    }
                ),
                'confirmed'
            ],
            'avatar' => 'nullable|string|max:150',
            'email' => [
                'required_if:id,',
                'email',
                'max:150',
                Rule::modelUnique(User::class, 'email'),
            ],
            'status' => 'required|in:'.implode(',', $allStatus),
        ];
    }

    /**
     * Get model resource
     *
     * @param  mixed  ...$params
     * @return string
     */
    protected function getModel(...$params): string
    {
        return User::class;
    }

    /**
     * Get title resource
     *
     * @param  mixed  ...$params
     * @return string
     */
    protected function getTitle(...$params): string
    {
        return trans('cms::app.users');
    }

    /**
     * Get data table resource
     *
     * @param  mixed  ...$params
     * @return UserDataTable|DataTable
     */
    protected function getDataTable(...$params): UserDataTable|DataTable
    {
        return new UserDataTable();
    }

    protected function getDataForForm(Model $model, ...$params): array
    {
        $data = $this->DataForForm($model);
        $data['allStatus'] = User::getAllStatus();
        return $data;
    }

    protected function afterSave(array $data, Model $model, ...$params): void
    {
        do_action('user.after_save', $data, $model);
    }

    /**
     * After Save model
     *
     * @param  array  $data
     * @param  Model  $model
     * @param  mixed  ...$params
     * @throws ValidationException
     */
    protected function beforeSave(array &$data, Model $model, ...$params): void
    {
        if ($password = Arr::get($data, 'password')) {
            Validator::make(
                $data,
                [
                    'password' => 'required|string|max:32|min:8|confirmed',
                    'password_confirmation' => 'required|string|max:32|min:8',
                ],
                [],
                [
                    'password' => trans('cms::app.password'),
                    'password_confirmation' => trans('cms::app.confirm_password'),
                ]
            )->validate();

            $model->setAttribute('password', Hash::make($password));
        }

        do_action('user.before_save', $data, $model);
    }
}
