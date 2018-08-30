<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

final class PermissionsDropTables extends Migration
{
    public function up(): void
    {
        $tables = config('permission.table_names');
        Schema::dropIfExists($tables['role_has_permissions']);
        Schema::dropIfExists($tables['model_has_roles']);
        Schema::dropIfExists($tables['model_has_permissions']);
        Schema::dropIfExists($tables['roles']);
        Schema::dropIfExists($tables['permissions']);
    }

    public function down(): void
    {
        // one-side migration, tenancy database needs to be clean
    }
}
