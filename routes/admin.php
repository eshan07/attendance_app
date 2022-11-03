<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => ['auth','is_admin']], function () {
//    Route::get('/home', [HomeController::class, 'adminHome'])->name('home');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', RoleController::class);
});
