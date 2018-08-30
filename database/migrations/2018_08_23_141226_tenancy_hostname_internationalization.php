<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class TenancyHostnameInternationalization extends Migration
{
    public function up(): void
    {
        Schema::table('hostnames', function (Blueprint $table): void {
            $table->string('locale', 80);
            $table->string('timezone', 32);
        });
    }

    public function down(): void
    {
        Schema::table('hostnames', function (Blueprint $table): void {
            $table->dropColumn('locale');
            $table->dropColumn('timezone');
        });
    }
}
