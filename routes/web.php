<?php

use Illuminate\Support\Facades\Route;
use Itpi\Http\Controllers\Panel\AdminController;
use Itpi\Http\Controllers\Panel\AuthController;
use Itpi\Http\Controllers\Panel\FeatureController;
use Itpi\Http\Controllers\Panel\HomeController;
use Itpi\Http\Controllers\Panel\ProfileController;
use Itpi\Http\Controllers\Panel\ProjectController;
use Itpi\Http\Controllers\Panel\SettingsController;
use Itpi\Http\Controllers\Panel\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (request()->getHost() == env('TELESCOPE_DOMAIN', 'telescope.eprocurement.id')) {
        return redirect('/telescope');
    }
    return view('welcome');
});

// Logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('panel')->middleware('auth')->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/save', [SettingsController::class, 'save'])->name('settings.save');
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update_image', [ProfileController::class, 'update_image'])->name('profile.update_image');
    // Manage Feature
    Route::get('/feature', [FeatureController::class, 'index'])->name('feature');
    Route::post('/feature/create', [FeatureController::class, 'create'])->name('feature.create');
    Route::post('/feature/update', [FeatureController::class, 'update'])->name('feature.update');
    Route::get('/feature/delete/{id}', [FeatureController::class, 'delete'])->name('feature.delete');
    // Manage Project
    Route::get('/project', [ProjectController::class, 'index'])->name('project');
    Route::post('/project/create', [ProjectController::class, 'create'])->name('project.create');
    Route::post('/project/update', [ProjectController::class, 'update'])->name('project.update');
    Route::get('/project/delete/{id}', [ProjectController::class, 'delete'])->name('project.delete');
    // Manage Admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::post('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/update', [AdminController::class, 'update'])->name('admin.update');
    Route::get('/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
    // Manage Users (Reset PIN)
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/reset_pin/{id}', [UserController::class, 'reset_pin'])->name('user.reset_pin');
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
});
