<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Controllers;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use IntelliShop\LaravelFoundation\Application\Entities\User;
use Laravel\Passport\PersonalAccessTokenResult;
use PHPUnit\Framework\TestCase;

final class AuthorizationRestControllerTest extends TestCase
{
    /**
     * @covers \IntelliShop\LaravelFoundation\Application\Controllers\AuthorizationRestController::<public>
     */
    public function testAuthorizeSuccess(): void
    {
        $configuration = $this->getMockBuilder(Config::class)->setMethods(['get', 'set'])->getMock();
        $configuration->expects($this->never())->method('set');
        $configuration->expects($this->once())->method('get')->willReturn('...');

        $token = $this->getMockBuilder(PersonalAccessTokenResult::class)->disableOriginalConstructor()->getMock();
        $token->accessToken = '#token';

        $user = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $user->expects($this->once())->method('createToken')->willReturn($token);

        $guard = $this->getMockBuilder(StatefulGuard::class)->getMock();
        $guard
            ->expects($this->once())->method('attempt')
            ->willReturnCallback(function (array $credentials): bool {
                return $credentials === ['email' => null, 'password' => null];
            });
        $guard->expects($this->once())->method('user')->willReturn($user);

        $manager = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->getMock();
        $manager->expects($this->once())->method('guard')->willReturn($guard);

        $request = $this->getMockBuilder(Request::class)->getMock();

        $positiveResponse = (new AuthorizationRestController())->authorize($request, $manager, $configuration);
        $this->assertSame(200, $positiveResponse->getStatusCode());
        $this->assertSame(['success' => ['token' => '#token']], $positiveResponse->getData(true));
    }

    /**
     * @covers \IntelliShop\LaravelFoundation\Application\Controllers\AuthorizationRestController::<public>
     */
    public function testAuthorizeFailure(): void
    {
        $guard = $this->getMockBuilder(StatefulGuard::class)->getMock();
        $guard->expects($this->once())->method('attempt')->willReturn(false);
        $guard->expects($this->never())->method('user');

        $manager = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->getMock();
        $manager->expects($this->once())->method('guard')->willReturn($guard);

        $configuration = $this->getMockBuilder(Config::class)->setMethods(['get', 'set'])->getMock();
        $configuration->expects($this->never())->method('set');
        $configuration->expects($this->never())->method('get');

        $request = $this->getMockBuilder(Request::class)->getMock();

        $negativeResponse = (new AuthorizationRestController())->authorize($request, $manager, $configuration);
        $this->assertSame(401, $negativeResponse->getStatusCode());
        $this->assertSame(['error' => 'Unauthorised'], $negativeResponse->getData(true));
    }
}
