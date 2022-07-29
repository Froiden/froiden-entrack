<?php

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

use App\Http\Controllers\EnvatoSettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UpdateAppController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('earning-chart', [SaleController::class, 'earningChart'])->name('sales.earningChart');
    Route::get('lean-back', [SaleController::class, 'leanBack'])->name('sales.lean');
    Route::post('lean-back', [SaleController::class, 'leanBack'])->name('sales.leanBack');

    Route::resource('sales', SaleController::class);
    Route::resource('products', ProductController::class);
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('verify', [LicenseController::class, 'verify'])->name('licenses.verify');
    Route::post('verifyCode', [LicenseController::class, 'verifyCode'])->name('licenses.verifyCode');
    Route::resource('licenses', LicenseController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('settings', EnvatoSettingController::class);
    Route::resource('users', UserController::class);
});