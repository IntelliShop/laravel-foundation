<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Hyn\Tenancy\Environment;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use IntelliShop\LaravelFoundation\Application\Entities\Hostname;
use PHPUnit\Framework\TestCase;

final class MultiTenantServiceProviderTest extends TestCase
{
    /**
     * @covers \IntelliShop\LaravelFoundation\MultiTenantServiceProvider::<public>
     */
    public function testInternationalizationFallback(): void
    {
        $hostname = $this->getMockBuilder(Hostname::class)->disableOriginalConstructor()->getMock();
        [$hostname->timezone, $hostname->locale] = [null, null];

        $environment = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $environment->expects($this->once())->method('hostname')->willReturn($hostname);

        $configuration = $this->getMockBuilder(Config::class)->setMethods(['get', 'set', 'has'])->getMock();
        $configuration
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturn(function (string $what): string {
                return $what;
            });
        $configuration
            ->expects($this->exactly(2))
            ->method('set')
            ->willReturn(function (array $settings): void {
                $this->assertArraySubset([Hostname::class, 'app.timezone', 'app.locale'], $settings);
            });

        $application = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        $application->expects($this->never())->method('setLocale');
        $application->expects($this->never())->method('make');

        (new MultiTenantServiceProvider($application))->boot($configuration, $environment);
    }

    /**
     * @covers \IntelliShop\LaravelFoundation\MultiTenantServiceProvider::<public>
     */
    public function testInternationalizationRewrite(): void
    {
        $hostname = $this->getMockBuilder(Hostname::class)->disableOriginalConstructor()->getMock();
        [$hostname->timezone, $hostname->locale] = ['CET', 'de'];

        $environment = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $environment->expects($this->once())->method('hostname')->willReturn($hostname);

        $configuration = $this->getMockBuilder(Config::class)->setMethods(['get', 'set', 'has'])->getMock();
        $configuration->expects($this->never())->method('get');
        $configuration
            ->expects($this->exactly(2))
            ->method('set')
            ->willReturn(function (array $settings): void {
                $this->assertSame('CET', $settings['app.timezone']);
                $this->assertSame('de', $settings['app.locale']);
            });

        $application = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        $application->expects($this->never())->method('setLocale');
        $application->expects($this->never())->method('make');

        (new MultiTenantServiceProvider($application))->boot($configuration, $environment);
    }
}
