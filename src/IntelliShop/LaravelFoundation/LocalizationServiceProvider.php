<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

final class LocalizationServiceProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $middlewares = [
            'localize'              => LaravelLocalizationRoutes::class,
            'localizationRedirect'  => LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => LocaleSessionRedirect::class,
        ];

        foreach ($middlewares as $alias => $class) {
            $router->pushMiddlewareToGroup('web', $class);
            $router->aliasMiddleware($alias, $class);
        }
    }
}
