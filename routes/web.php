<?php

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
  return view('welcome');
});

Auth::routes(['verify' => true, 'confirm' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home/secret', function(){
  dd('This is Secret Page of User');
})->middleware(['auth','verified','password.confirm']);


// Admin Routes
Route::group(['prefix' => 'admin'], function(){
  Route::view('/','admin.home')->name('admin.home')->middleware(['auth:admin','verified']);
  Route::get('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
  Route::post('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
  Route::post('logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('admin.logout');

  Route::get('password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
  Route::post('password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
  Route::get('password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
  Route::post('password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('admin.password.update');

  Route::get('email/verify', [App\Http\Controllers\Admin\Auth\VerificationController::class, 'show'])->name('admin.verification.notice');
  Route::get('email/verify/{id}/{hash}', [App\Http\Controllers\Admin\Auth\VerificationController::class, 'verify'])->name('admin.verification.verify');
  Route::post('email/resend', [App\Http\Controllers\Admin\Auth\VerificationController::class, 'resend'])->name('admin.verification.resend');

  Route::get('password/confirm', [App\Http\Controllers\Admin\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('admin.password.confirm');
  Route::post('password/confirm', [App\Http\Controllers\Admin\Auth\ConfirmPasswordController::class, 'confirm']);


  Route::get('secret', function(){
    dd('This is Secret Page of Admin');
  })->middleware(['auth:admin','verified','password.confirm']);
});
// End Admin Routes