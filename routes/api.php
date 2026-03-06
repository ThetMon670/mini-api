<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherExportController;
use App\Http\Controllers\VoucherItemController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post("/register", "register")->name('register');
    Route::post("/login", "login")->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('customer', CustomerController::class);
    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::post("/logout", [ProfileController::class, "logout"]);
        Route::post("change-name", [ProfileController::class, "changeName"]);
        Route::post("change-password", [ProfileController::class, "changePassword"]);
        Route::post("change-profile-image", [ProfileController::class, "changeProfileImage"]);
        Route::get("show", [ProfileController::class, "show"]);
    });
    Route::resource('categories', CategoryController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('images', ImageController::class)
        ->only(['store', 'destroy']);
    Route::apiResource('/items', VoucherItemController::class)->only('index');
    Route::apiResource('/vouchers', VoucherController::class)->except('update');

    Route::get('/export/vouchers/csv', [ExportController::class, 'exportVouchersCSV']);
    Route::get('/export/voucher-items/csv', [ExportController::class, 'exportVoucherItemsCSV']);
});
