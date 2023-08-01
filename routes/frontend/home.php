<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\User\ServerController;
use App\Http\Controllers\Frontend\User\AddServerController;
use App\Http\Controllers\Frontend\User\LogController;
use App\Http\Controllers\Frontend\User\ConfigController;
use App\Http\Controllers\Frontend\User\AddUserController;
use App\Http\Controllers\Frontend\User\RegisterUserController;
use App\Http\Controllers\Frontend\User\ServerDetailController;
use App\Http\Controllers\Frontend\User\ServerLatencyController;
use App\Http\Controllers\Frontend\User\ServerMetricController;
/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the password is expired
 */
Route::group(['middleware' => ['auth', 'password_expires']], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        // User Dashboard Specific
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Account Specific
        Route::get('account', [AccountController::class, 'index'])->name('account');

        // User Profile Specific
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
		
		// User Server Specific
        Route::get('server', [ServerController::class, 'index'])->name('server');
		
		// User Add Server Specific
        Route::get('addserver', [AddServerController::class, 'index'])->name('addserver');
		
		// User Log
        Route::get('log', [LogController::class, 'index'])->name('log');

        // User Log
        Route::patch('log/filter', [LogController::class, 'filter'])->name('log.filter');
		
		// User Config
        Route::get('config', [ConfigController::class, 'index'])->name('config');
		
		// User Add
        Route::get('adduser', [AddUserController::class, 'index'])->name('adduser');
		
		// User Register
        Route::get('registeruser', [RegisterUserController::class, 'index'])->name('registeruser');

        // Edit User
        Route::get('adduser/{id}/edit', [AddUserController::class, 'edit'])->name('editUser');

        // Update User
        Route::patch('adduser/update', [AddUserController::class, 'update'])->name('adduser.update');

        // Delete User
        Route::get('adduser/delete/{id}', [AddUserController::class, 'destroy'])->name('adduser.delete');

        // Add Server
        Route::patch('server/add', [AddServerController::class, 'create'])->name('addserver.create');

        // Edit Server
        Route::get('server/{id}/edit', [AddServerController::class, 'edit'])->name('editserver');

        // Update Server
        Route::patch('server/update', [AddServerController::class, 'update'])->name('server.update');

        // Delete Server
        Route::get('server/delete/{id}', [AddServerController::class, 'destroy'])->name('server.delete');

        // Activate Server
        Route::get('server/activate/{id}', [AddServerController::class, 'active'])->name('server.active');

        // Deactivate Server
        Route::get('server/deactivate/{id}', [AddServerController::class, 'deactive'])->name('server.deactive');

        // Detail Server
        Route::get('server/{id}/detail', [ServerDetailController::class, 'index'])->name('serverdetail');

        // Update Server Live
        Route::get('update/server', [ServerController::class, 'update'])->name('update.server');

        // Delete Log
        Route::get('log/delete', [LogController::class, 'destroy'])->name('log.delete');

        // Server Latency
        Route::get('server/{id}/latency', [ServerLatencyController::class, 'index'])->name('serverlatency');

        // Server Metric
        Route::get('server/{id}/metric/', [ServerMetricController::class, 'index'])->name('servermetric');

        // filter latency
        Route::patch('server/filter/latency', [ServerLatencyController::class, 'filter'])->name('serverlatency.filter');

        // Activate User
        Route::get('user/activate/{id}', [AddUserController::class, 'active'])->name('user.active');

        // Deactivate User
        Route::get('user/deactivate/{id}', [AddUserController::class, 'deactive'])->name('user.deactive');
    });
});
