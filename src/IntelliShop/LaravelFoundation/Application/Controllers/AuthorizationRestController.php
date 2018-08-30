<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Controllers;

use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthorizationRestController
{
    public function authorize(Request $request, AuthManager $auth): Response
    {
        /** @var \Illuminate\Contracts\Auth\StatefulGuard $guard */
        $guard = $auth->guard();
        if ($guard->attempt(['email' => $request->post('email'), 'password' => $request->post('password')])) {
            /** @var \IntelliShop\LaravelFoundation\Application\Entities\User|null $user */
            $user = $guard->user();
            if ($user !== null) {
                $token = $user->createToken(config('app.name'))->accessToken;

                return new JsonResponse(['success' => ['token' => $token]], 200);
            }
        }

        return new JsonResponse(['error' => 'Unauthorised'], 401);
    }
}
