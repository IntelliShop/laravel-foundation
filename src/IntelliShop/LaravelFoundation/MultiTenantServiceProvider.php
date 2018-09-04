<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Assert\Assertion;
use Hyn\Tenancy\Environment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

final class MultiTenantServiceProvider extends ServiceProvider
{
    public function boot(Config $configuration, Environment $environment): void
    {
        Assertion::file(($path = __DIR__.'/../../..').'/composer.json');

        /* sets internationalization defaults as per hostname configuration */
        $hostname = $environment->hostname();
        if ($hostname !== null) {
            $configuration->set([
                'app.timezone' => $hostname->timezone ?: $configuration->get('app.timezone'),
                'app.locale'   => $locale = $hostname->locale ?: $configuration->get('app.locale'),
            ]);
        }

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom($path.'/database/migrations');
        }
    }
}
