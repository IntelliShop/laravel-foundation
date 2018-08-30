<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Entities\Passport;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Laravel\Passport\AuthCode as OriginalModel;

final class AuthCode extends OriginalModel
{
    use UsesTenantConnection;
}
