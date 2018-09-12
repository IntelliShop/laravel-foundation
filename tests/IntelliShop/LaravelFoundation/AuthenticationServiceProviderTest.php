<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use IntelliShop\LaravelFoundation\Application\Console\PassportInstallCommand;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\AuthCode;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\Client;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\PersonalAccessClient;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\Token;
use IntelliShop\LaravelFoundation\Application\Entities\User;
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
        $provider->expects($this->once())->method('loadRoutesFrom');
        $provider->expects($this->once())->method('publishes');

        $configuration = $this->getMockBuilder(Repository::class)->getMock();
        $configuration->expects($this->never())->method('get');
        $configuration
            ->expects($this->once())->method('set')
            ->willReturn(function (array $settings): void {
                $this->assertArraySubset(['passport', User::class], $settings);
            });

        $provider->boot($configuration);

        $this->assertSame(Token::class, Passport::tokenModel());
        $this->assertSame(Client::class, Passport::clientModel());
        $this->assertSame(AuthCode::class, Passport::authCodeModel());
        $this->assertSame(PersonalAccessClient::class, Passport::personalAccessClientModel());
    }

    /**
     * @covers \IntelliShop\LaravelFoundation\AuthenticationServiceProvider::<public>
     */
    public function testRegister(): void
    {
        $application = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        $application
            ->expects($this->exactly(2))->method('make')
            ->willReturnCallback(function (string $what): object {
                return $this->getMockBuilder($what)->disableOriginalConstructor()->getMock();
            });
        $application
            ->expects($this->once())->method('singleton')
            ->willReturnCallback(function (string $what, \Closure $factory) use ($application): object {
                $this->assertSame($what, PassportInstallCommand::class);

                return $factory($application);
            });

        (new AuthenticationServiceProvider($application))->register();
    }
}
