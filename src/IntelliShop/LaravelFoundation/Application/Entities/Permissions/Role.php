<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Entities\Permissions;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\Permission\Models\Role as OriginalModel;

final class Role extends OriginalModel
{
    use UsesTenantConnection;
}
