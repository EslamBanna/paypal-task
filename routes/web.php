<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Paymentcontroller;
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

Route::get('/', function () {
    return redirect()->route('user.login');
});

Route::get('/login', [AuthController::class, 'loginPage'])->middleware('guest:user');
Route::post('login', [AuthController::class, 'loginUser'])->name('user.login')->middleware('guest:user');
Route::get('/register', [AuthController::class, 'registerPage'])->name('user.register.page');
Route::post('/register', [AuthController::class, 'register'])->name('user.register');

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('pay-page', [Paymentcontroller::class, 'payView'])->name('payment.view');
    Route::post('pay', [Paymentcontroller::class, 'pay'])->name('payment.pay');
    Route::get('success-pay/{payment_id}', [Paymentcontroller::class, 'successPay'])->name('payment.success');
    Route::get('error-pay/{payment_id}', [Paymentcontroller::class, 'errorPay'])->name('payment.error');
    Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
});