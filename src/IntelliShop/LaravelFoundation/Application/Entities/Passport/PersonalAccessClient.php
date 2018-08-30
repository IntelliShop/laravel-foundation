<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Entities\Passport;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Laravel\Passport\PersonalAccessClient as OriginalModel;

final class PersonalAccessClient extends OriginalModel
{
    use UsesTenantConnection;
}
