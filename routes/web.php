<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AccountManagementController;
use App\Http\Controllers\Backend\ListRegistrationController;
use App\Http\Controllers\Backend\GuestBookController;
use App\Http\Controllers\Frontend\RegistrationController;

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
    return view('auth.login');
});

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'administrator'], function() {    
        Route::get('dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        Route::get('get-city', [ListRegistrationController::class, 'getCity'])->name('get-city');
        Route::resource('account', AccountManagementController::class);
        Route::resource('list-registration', ListRegistrationController::class);
        Route::resource('guest-book', GuestBookController::class);
    });

});

Route::get('registration-city', [RegistrationController::class, 'getCity'])->name('registration-city');
Route::get('registration', [RegistrationController::class, 'index'])->name('registration');
Route::post('registration', [RegistrationController::class, 'store'])->name('registration-store');
Route::get('success', function(){
    return view('frontend.email');
});

require __DIR__.'/auth.php';
