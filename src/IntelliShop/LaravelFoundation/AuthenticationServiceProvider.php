<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Assert\Assertion;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Database\Connection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use IntelliShop\LaravelFoundation\Application\Console\PassportInstallCommand;
use IntelliShop\LaravelFoundation\Application\Entities\AuthCode;
use IntelliShop\LaravelFoundation\Application\Entities\Client;
use IntelliShop\LaravelFoundation\Application\Entities\PersonalAccessClient;
use IntelliShop\LaravelFoundation\Application\Entities\Token;
use Laravel\Passport\Passport;

final class AuthenticationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Assertion::file(($path = __DIR__.'/../../..').'/composer.json');
        $this->loadRoutesFrom($path.'/routes/routes.php');

        Passport::useTokenModel(Token::class);
        Passport::useClientModel(Client::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

        if ($this->app->runningInConsole()) {
            /* laravel/passport migrations are needed in the tenant scope */
            Assertion::file(($path .= '/../../laravel/passport').'/composer.json');
            $this->publishes([$path.'/database/migrations/' => 'database/migrations/tenant/'], 'tenant-migrations');
        }
    }

    public function register(): void
    {
        $this->app->singleton(PassportInstallCommand::class, function (Application $application) {
            return new PassportInstallCommand(
                $application->make(WebsiteRepository::class),
                $application->make(Connection::class)
            );
        });

        $this->commands([PassportInstallCommand::class]);
    }
}
