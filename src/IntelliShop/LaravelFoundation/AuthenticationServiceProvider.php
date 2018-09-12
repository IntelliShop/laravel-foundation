<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Assert\Assertion;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Database\Connection;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use IntelliShop\LaravelFoundation\Application\Console\PassportInstallCommand;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\AuthCode;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\Client;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\PersonalAccessClient;
use IntelliShop\LaravelFoundation\Application\Entities\Passport\Token;
use IntelliShop\LaravelFoundation\Application\Entities\User;
use Laravel\Passport\Passport;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function boot(Repository $configuration): void
    {
        Assertion::file(($path = __DIR__.'/../../..').'/composer.json');
        $this->loadRoutesFrom($path.'/routes/routes.php');

        $configuration->set([
            'auth.guards.api.driver'     => 'passport',
            'auth.providers.users.model' => User::class,
        ]);

        Passport::useTokenModel(Token::class);
        Passport::useClientModel(Client::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

        if ($this->app->runningInConsole()) {
            /* laravel/passport migrations are needed in the tenant scope */
            $originalPackagePath = $path.'/../../laravel/passport';
            $this->publishes(
                [$originalPackagePath.'/database/migrations/' => 'database/migrations/tenant/'],
                'tenant-migrations'
            );
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
