<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\RepairLogController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ExportController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Login: dibatasi 10 percobaan per menit untuk mencegah brute-force
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users', [AuthController::class, 'users']);

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD & REPORT
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    Route::get('/assets/export', [ExportController::class, 'exportAssets']);

    /*
    |--------------------------------------------------------------------------
    | ASSETS
    |--------------------------------------------------------------------------
    */

    Route::apiResource('assets', AssetController::class);

    /*
    |--------------------------------------------------------------------------
    | TICKETS
    |--------------------------------------------------------------------------
    */

    Route::apiResource('tickets', TicketController::class);

    /*
    |--------------------------------------------------------------------------
    | REPAIR LOGS
    |--------------------------------------------------------------------------
    */

    Route::apiResource('repair-logs', RepairLogController::class);
    
    

});