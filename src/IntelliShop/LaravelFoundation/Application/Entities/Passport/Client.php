<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Entities\Passport;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Laravel\Passport\Client as OriginalModel;

final class Client extends OriginalModel
{
    use UsesTenantConnection;
}
