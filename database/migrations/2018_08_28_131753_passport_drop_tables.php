<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

final class PassportDropTables extends Migration
{
    public function up(): void
    {
        Schema::drop('oauth_access_tokens');
        Schema::drop('oauth_auth_codes');
        Schema::drop('oauth_clients');
        Schema::drop('oauth_personal_access_clients');
        Schema::drop('oauth_refresh_tokens');
    }

    public function down(): void
    {
        // one-side migration, tenancy database needs to be clean
    }
}
