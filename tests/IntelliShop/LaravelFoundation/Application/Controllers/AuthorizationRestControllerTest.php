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
    public function testAuthorize(): void
    {
        $configuration = $this->getMockBuilder(Config::class)->setMethods(['get', 'set'])->getMock();
        $configuration->expects($this->never())->method('set');
        $configuration->expects($this->once())->method('get')->willReturn('...');

        $token = $this->getMockBuilder(PersonalAccessTokenResult::class)->disableOriginalConstructor()->getMock();
        $token->accessToken = '#token';

        $user = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $user->expects($this->once())->method('createToken')->willReturn($token);

        $attemptResult = true;
        $guard = $this->getMockBuilder(StatefulGuard::class)->getMock();
        $guard
            ->expects($this->exactly(2))->method('attempt')
            ->willReturnCallback(function (array $credentials) use (&$attemptResult): bool {
                $this->assertSame(['email' => null, 'password' => null], $credentials);
                $result = $attemptResult;
                $attemptResult = ! $attemptResult;

                return $result;
            });
        $guard->expects($this->once())->method('user')->willReturn($user);

        $manager = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->getMock();
        $manager->expects($this->exactly(2))->method('guard')->willReturn($guard);

        $request = $this->getMockBuilder(Request::class)->getMock();

        $controller = new AuthorizationRestController();

        $positiveResponse = $controller->authorize($request, $manager, $configuration);
        $this->assertSame(200, $positiveResponse->getStatusCode());
        $this->assertSame(['success' => ['token' => '#token']], $positiveResponse->getData(true));

        $negativeResponse = $controller->authorize($request, $manager, $configuration);
        $this->assertSame(401, $negativeResponse->getStatusCode());
        $this->assertSame(['error' => 'Unauthorised'], $negativeResponse->getData(true));
    }
}
