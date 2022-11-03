<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\AttendanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
//
//Route::get('/', function () {
//    return view('layouts.dashboard');
//});
//Route::get('/login', function (){
//   return view('layouts.login');
//});

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index']);
    Route::post('/attendance/statement', [AttendanceController::class, 'statement'])->name('attendances.statement');
    Route::resource('attendances', AttendanceController::class);
});
