<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\API\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Juzaweb\CMS\Http\Controllers\FrontendController;

class ApiTokenController extends FrontendController
{
    public function generate(Request $request): JsonResponse|RedirectResponse
    {
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return $this->success(
            [
                'token' => $token,
                'message' => 'Token generated successfully.',
            ],
        );
    }
}
