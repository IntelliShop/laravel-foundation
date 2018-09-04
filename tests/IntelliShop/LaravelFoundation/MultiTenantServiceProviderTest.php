<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Environment;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class MultiTenantServiceProviderTest extends TestCase
{
    /**
     * @covers \IntelliShop\LaravelFoundation\MultiTenantServiceProvider::<public>
     */
    public function testInternationalizationFallback(): void
    {
        /** @var \stdClass $hostname */
        $hostname = $this->getMockBuilder(Hostname::class)->getMock();
        [$hostname->timezone, $hostname->locale] = [null, null];

        $environment = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $environment->expects($this->once())->method('hostname')->willReturn($hostname);

        $configuration = $this->getMockBuilder(ContainerInterface::class)->setMethods(['get', 'set', 'has'])->getMock();
        $configuration
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturn(function (string $what): string {
                return $what;
            });
        $configuration
            ->expects($this->once())
            ->method('set')
            ->willReturn(function (array $settings): void {
                $this->assertSame('app.timezone', $settings['app.timezone']);
                $this->assertSame('app.locale', $settings['app.locale']);
            });

        $application = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        $application->expects($this->never())->method('setLocale');
        $application
            ->expects($this->exactly(2))
            ->method('make')
            ->willReturnCallback(function (string $what) use ($configuration, $environment): object {
                return $what === 'config' ? $configuration : $environment;
            });

        (new MultiTenantServiceProvider($application))->boot($configuration, $environment);
    }

    /**
     * @covers \IntelliShop\LaravelFoundation\MultiTenantServiceProvider::<public>
     */
    public function testInternationalizationRewrite(): void
    {
        /** @var \stdClass $hostname */
        $hostname = $this->getMockBuilder(Hostname::class)->getMock();
        [$hostname->timezone, $hostname->locale] = ['CET', 'de'];

        $environment = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $environment->expects($this->once())->method('hostname')->willReturn($hostname);

        $configuration = $this->getMockBuilder(ContainerInterface::class)->setMethods(['get', 'set', 'has'])->getMock();
        $configuration->expects($this->never())->method('get');
        $configuration
            ->expects($this->once())
            ->method('set')
            ->willReturn(function (array $settings): void {
                $this->assertSame('CET', $settings['app.timezone']);
                $this->assertSame('de', $settings['app.locale']);
            });

        $application = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        $application->expects($this->never())->method('setLocale');
        $application
            ->expects($this->exactly(2))
            ->method('make')
            ->willReturnCallback(function (string $what) use ($configuration, $environment): object {
                return $what === 'config' ? $configuration : $environment;
            });

        (new MultiTenantServiceProvider($application))->boot($configuration, $environment);
    }
}
