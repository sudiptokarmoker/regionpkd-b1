<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CheckNipController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\DevController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MakePaymentController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\ThankYouController;
use App\Http\Controllers\ValidationPaymentController;
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
    return redirect('home');
});

Route::get('/dev', [DevController::class, 'index']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/check-nip', CheckNipController::class)->name('check-nip');
    Route::post('/payment', MakePaymentController::class)->name('make-payment');
    Route::any('/validation', ValidationPaymentController::class)->name('validate-payment');
    Route::post('/thank-you', ThankYouController::class)->name('thanks-page');
    Route::post('/change-password', ChangePasswordController::class)->name('change-password');
    //Route::resource('opinions', OpinionController::class);
    /**
     * route for admin
    */
    Route::get('/user-control', [AdminController::class, 'userLists'])->name('admin.user.lists');
    Route::get('/grid-test', [DevController::class, 'gridRender'])->name('grid.render.test');
    Route::get('/change-password-form/{user_id}', [AdminController::class, 'changePassword'])->name('change.password.form');
    Route::post('/user-info-update', [AdminController::class, 'updateUserInfo'])->name('user.info.update');
});

Auth::routes();