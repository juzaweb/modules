<?php

namespace Juzaweb\CMS\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;

trait ResponseMessage
{
    protected function response($data, $status): JsonResponse|RedirectResponse
    {
        if (! is_array($data)) {
            $data = [$data];
        }

        // if (request()->has('redirect')) {
        //     $data['redirect'] = request()->input('redirect');
        // }

        if (request()->ajax() || request()->isJson()) {
            return response()->json(
                [
                    'status' => $status,
                    'data' => $data,
                ]
            );
        }

        $data['status'] = $status ? 'success' : 'error';

        if (!empty($data['redirect'])) {
            return redirect()->to($data['redirect'])->with(Arr::except($data, 'redirect'));
        }

        $back = back()->withInput()->with($data);

        if (empty($status)) {
            $back->withErrors([$data['message']]);
        }

        return $back;
    }

    /**
     * Response success message
     *
     * @param string|array $message
     * @return JsonResponse|RedirectResponse
     */
    protected function success(string|array $message): JsonResponse|RedirectResponse
    {
        if (is_string($message)) {
            $message = ['message' => $message];
        }

        return $this->response($message, true);
    }

    /**
     * Response error message
     *
     * @param string|array $message
     * @return JsonResponse|RedirectResponse
     */
    protected function error(string|array $message): JsonResponse|RedirectResponse
    {
        if (is_string($message)) {
            $message = ['message' => $message];
        }

        return $this->response($message, false);
    }
}
