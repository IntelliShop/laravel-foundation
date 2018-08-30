<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Assert\Assertion;
use Illuminate\Support\ServiceProvider;
use IntelliShop\LaravelFoundation\Application\Entities\Permissions\Permission;
use IntelliShop\LaravelFoundation\Application\Entities\Permissions\Role;

final class PermissionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $configuration = $this->app->make('config');
        $configuration->set('permission.models.permission', Permission::class);
        $configuration->set('permission.models.role', Role::class);

        if ($this->app->runningInConsole()) {
            /* spatie/laravel-permission migrations are needed in the tenant scope */
            Assertion::file(($path = __DIR__.'/../../..').'/composer.json');
            Assertion::file(($path .= '/../../spatie/laravel-permission').'/composer.json');
            $target = sprintf('database/migrations/tenant/%s_create_permission_tables.php', date('Y_m_d_His'));
            $this->publishes([$path.'/database/migrations/create_permission_tables.php.stub' => $target], 'tenant-migrations');
        }
    }
}
