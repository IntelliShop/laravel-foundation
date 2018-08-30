<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Entities;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\Permission\Models\Permission as OriginalModel;

final class Permission extends OriginalModel
{
    use UsesTenantConnection;
}
