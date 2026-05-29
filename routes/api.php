<?php

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\PublicationController as AdminPublicationController;
use App\Http\Controllers\Api\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Summit registration (public form on the frontend).
Route::post('/registrations', [RegistrationController::class, 'store']);

// Publications (read-only public endpoints).
Route::get('/publications', [PublicationController::class, 'index']);
Route::get('/publications/filters', [PublicationController::class, 'filters']);
Route::get('/publications/{publication:slug}', [PublicationController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Admin API
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    // Auth — public endpoints.
    Route::post('auth/register', [AdminAuthController::class, 'register']);
    Route::post('auth/login', [AdminAuthController::class, 'login']);

    // Authenticated admin-only endpoints.
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('auth/me', [AdminAuthController::class, 'me']);
        Route::post('auth/logout', [AdminAuthController::class, 'logout']);

        // Publications CRUD.
        Route::apiResource('publications', AdminPublicationController::class);

        // Registrations management (no create — registrations come from the public form).
        Route::get('registrations/stats', [AdminRegistrationController::class, 'stats']);
        Route::get('registrations', [AdminRegistrationController::class, 'index']);
        Route::get('registrations/{registration}', [AdminRegistrationController::class, 'show']);
        Route::match(['put', 'patch'], 'registrations/{registration}', [AdminRegistrationController::class, 'update']);
        Route::delete('registrations/{registration}', [AdminRegistrationController::class, 'destroy']);
    });
});
