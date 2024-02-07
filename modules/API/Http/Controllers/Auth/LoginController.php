<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\API\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juzaweb\API\Http\Requests\Auth\LoginRequest;
use Juzaweb\Backend\Http\Resources\UserResource;
use Juzaweb\CMS\Http\Controllers\ApiController;
use Juzaweb\CMS\Models\User;

class LoginController extends ApiController
{
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = $request->user();

        $token = $user->createToken('auth_token');

        return $this->respondWithToken($token, $user);
    }

    public function profile(Request $request): UserResource
    {
        $user = $request->user('api');

        return UserResource::make($user)->withAdminField(false);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user('api')->token()->delete();

        return $this->restSuccess([], 'Successfully logged out.');
    }

    protected function respondWithToken($token, User $user): JsonResponse
    {
        return $this->restSuccess(
            [
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->token->expires_at,
                'user' => UserResource::make($user)->withAdminField(false),
            ],
            'Successfully login.'
        );
    }
}
