<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Assert\Assertion;
use Hyn\Tenancy\Environment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use IntelliShop\LaravelFoundation\Application\Entities\Hostname;

final class MultiTenantServiceProvider extends ServiceProvider
{
    public function boot(Config $configuration, Environment $environment): void
    {
        Assertion::file(($path = __DIR__.'/../../..').'/composer.json');
        $configuration->set(['tenancy.models.hostname' => Hostname::class]);

        /* sets internationalization defaults as per hostname configuration */
        /** @var Hostname|null $hostname */
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
