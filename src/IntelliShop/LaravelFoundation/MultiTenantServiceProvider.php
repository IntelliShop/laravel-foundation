<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Assert\Assertion;
use Hyn\Tenancy\Environment;
use Illuminate\Support\ServiceProvider;

final class MultiTenantServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Assertion::file(($path = __DIR__.'/../../..').'/composer.json');

        /** @var \Illuminate\Foundation\Application $application */
        $application = $this->app;
        $configuration = $application->make('config');

        /* sets internationalization defaults as per hostname configuration */
        $hostname = $application->make(Environment::class)->hostname();
        if ($hostname !== null) {
            $configuration->set([
                'app.timezone' => $hostname->timezone ?: $configuration->get('app.timezone'),
                'app.locale'   => $locale = $hostname->locale ?: $configuration->get('app.locale'),
            ]);
        }

        if ($application->runningInConsole()) {
            $this->loadMigrationsFrom($path.'/database/migrations');
        }
    }
}
