<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Illuminate\Foundation\Application;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\AuthCode;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\Client;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\PersonalAccessClient;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\Token;
use Laravel\Passport\Passport;
use PHPUnit\Framework\TestCase;

final class AuthenticationServiceProviderTest extends TestCase
{
    /**
     * @covers \IntelliShop\LaravelFoundation\AuthenticationServiceProvider::<public>
     */
    public function testBoot(): void
    {
        $application = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        $application->expects($this->once())->method('runningInConsole')->willReturn(true);

        $provider = $this
            ->getMockBuilder(AuthenticationServiceProvider::class)
            ->setConstructorArgs([$application])
            ->setMethods(['loadRoutesFrom', 'publishes'])
            ->getMock();
        $provider->expects($this->once())->method('loadRoutesFrom')->willReturn(null);
        $provider->expects($this->once())->method('publishes')->willReturn(null);

        $provider->boot();

        $this->assertSame(Token::class, Passport::tokenModel());
        $this->assertSame(Client::class, Passport::clientModel());
        $this->assertSame(AuthCode::class, Passport::authCodeModel());
        $this->assertSame(PersonalAccessClient::class, Passport::personalAccessClientModel());
    }
}
