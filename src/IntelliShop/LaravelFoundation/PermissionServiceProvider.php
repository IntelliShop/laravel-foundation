<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation;

use Assert\Assertion;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use IntelliShop\LaravelFoundation\Application\Entities\Permissions\Permission;
use IntelliShop\LaravelFoundation\Application\Entities\Permissions\Role;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot(Config $configuration): void
    {
        Assertion::file(($path = __DIR__.'/../../..').'/composer.json');
        $configuration->set([
            'permission.models.permission' => Permission::class,
            'permission.models.role'       => Role::class,
        ]);

        if ($this->app->runningInConsole()) {
            /* spatie/laravel-permission migrations are needed in the tenant scope */
            $originalPackagePath = $path.'/../../spatie/laravel-permission';
            $target = sprintf('database/migrations/tenant/%s_create_permission_tables.php', date('Y_m_d_His'));
            $this->publishes(
                [$originalPackagePath.'/database/migrations/create_permission_tables.php.stub' => $target],
                'tenant-migrations'
            );
        }
    }
}
