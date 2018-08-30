<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Laravel\FrameworkCustomization\Application\Controllers\AuthorizationRestController;
use Laravel\Passport\RouteRegistrar;

/* extracted from \Laravel\Passport\Passport::routes */
Route::group(['prefix' => 'oauth', 'namespace' => '\Laravel\Passport\Http\Controllers'], function ($router): void {
    (new RouteRegistrar($router))->all();
});

Route::group(['prefix' => 'api'], function (): void {
    Route::post('authorize', AuthorizationRestController::class.'@authorize');
});
