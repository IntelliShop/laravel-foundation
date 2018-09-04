<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use PHPUnit\Framework\TestCase;

final class LocalizationServiceProviderTest extends TestCase
{
    /**
     * @covers \IntelliShop\LaravelFoundation\LocalizationServiceProvider::<public>
     */
    public function testMiddlewaresRegistration(): void
    {
        $application = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();

        $router = $this->getMockBuilder(Router::class)->disableOriginalConstructor()->getMock();
        $router->expects($this->exactly(3))->method('pushMiddlewareToGroup');
        $router->expects($this->exactly(3))->method('aliasMiddleware');

        (new LocalizationServiceProvider($application))->boot($router);
    }
}
