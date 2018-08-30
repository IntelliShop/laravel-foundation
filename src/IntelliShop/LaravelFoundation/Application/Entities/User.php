<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Entities;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $name
 * @property string $email
 */
final class User extends Authenticatable
{
    use HasRoles, UsesTenantConnection, HasApiTokens, Notifiable;

    /** @var string[] */
    protected $fillable = ['name', 'email', 'password'];

    /** @var string[] */
    protected $hidden = ['password', 'remember_token'];
}
