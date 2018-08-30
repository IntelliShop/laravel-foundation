<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

final class UserDropTables extends Migration
{
    public function up(): void
    {
        Schema::drop('users');
        Schema::drop('password_resets');
    }

    public function down(): void
    {
        // one-side migration, tenancy database needs to be clean
    }
}
