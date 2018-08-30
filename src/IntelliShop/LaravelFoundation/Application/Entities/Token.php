<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Entities;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Laravel\Passport\Token as OriginalModel;

final class Token extends OriginalModel
{
    use UsesTenantConnection;
}
