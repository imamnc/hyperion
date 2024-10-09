<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Itpi\Http\Controllers\Api\AuthController;
use Itpi\Http\Controllers\Api\MenuController;
use Itpi\Http\Controllers\Api\UserController;
use Itpi\Http\Controllers\Api\ContractController;
use Itpi\Http\Controllers\Api\PengadaanController;
use Itpi\Http\Controllers\Api\PRController;
use Itpi\Http\Controllers\Api\VendorController;


/*
===================================================================
 OPEN ENDPOINT
===================================================================
*/

Route::post('login', [AuthController::class, 'login']);
Route::get('projects', [MenuController::class, 'projects']);

/*
===================================================================
 AUTHENTICATED ENDPOINT
===================================================================
*/
Route::middleware('auth:sanctum')->group(function () {
    // Fitur User
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'show']);
        Route::post('/check-pin', [AuthController::class, 'checkPin']);
        Route::post('/reset-pin', [UserController::class, 'resetPin']);
        Route::get('/menu', [UserController::class, 'menu']);
    });
    // Fitur Vendor
    Route::prefix('vendor')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->middleware('feature:vms');
        Route::get('/blacklist', [VendorController::class, 'blacklist'])->middleware('feature:blacklist');
    });
    // Fitur Pengadaan
    Route::prefix('pengadaan')->middleware('feature:procurement')->group(function () {
        Route::get('/', [PengadaanController::class, 'index']);
        Route::get('/detail', [PengadaanController::class, 'detail']);
    });
    // Fitur Purchase Requisition
    Route::prefix('pr')->middleware('feature:pr')->group(function () {
        Route::get('/', [PRController::class, 'index']);
        Route::get('/detail', [PRController::class, 'detail']);
    });
    // Fitur Contract
    Route::prefix('contract')->middleware('feature:contract')->group(function () {
        Route::get('/', [ContractController::class, 'index']);
        Route::get('/detail', [ContractController::class, 'detail']);
        Route::get('/documents', [ContractController::class, 'documents']);
    });
});
