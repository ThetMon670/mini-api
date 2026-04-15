<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuUnitsController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes are versioned under /api/v1
|
*/

Route::prefix('v1')->group(function () {

    // ------------------------
    // Root route for API v1
    // ------------------------
    Route::get('/', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API v1 is running'
        ]);
    });

    // ------------------------
    // Public Auth routes
    // ------------------------
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
    });

    // ------------------------
    // Protected routes
    // ------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Customers
        Route::apiResource('customer', CustomerController::class);


        // Profile routes
        Route::prefix('profile')->controller(ProfileController::class)->group(function () {
            Route::post('logout', 'logout');
            Route::post('change-name', 'changeName');
            Route::post('change-password', 'changePassword');
            Route::post('change-profile-image', 'changeProfileImage');
            Route::get('show', 'show');
        });

        // Categories, Menus, Images
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('menus', MenuController::class);
        Route::get('menu-units', [MenuUnitsController::class, 'menuUnits']);
        Route::apiResource("photos", PhotoController::class)->only(["store", "destroy"]);

        Route::get('vouchers/export', [VoucherController::class, 'voucherExport']);
        Route::get('/voucher-items/export', [VoucherController::class, 'voucherItemExport']);

        Route::apiResource('vouchers', VoucherController::class)->only(['index', 'store', 'show']);
    });
});