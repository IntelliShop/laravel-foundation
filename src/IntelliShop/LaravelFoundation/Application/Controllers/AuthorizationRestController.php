<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Controllers;

use Illuminate\Auth\AuthManager;
use Illuminate\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthorizationRestController
{
    public function authorize(Request $request, AuthManager $auth, Repository $configuration): JsonResponse
    {
        /** @var \Illuminate\Contracts\Auth\StatefulGuard $guard */
        $guard = $auth->guard();
        if ($guard->attempt(['email' => $request->post('email'), 'password' => $request->post('password')])) {
            /** @var \IntelliShop\LaravelFoundation\Application\Entities\User|null $user */
            $user = $guard->user();
            if ($user !== null) {
                $token = $user->createToken($configuration->get('app.name'))->accessToken;

                return new JsonResponse(['success' => ['token' => $token]], 200);
            }
        }

        return new JsonResponse(['error' => 'Unauthorised'], 401);
    }
}
