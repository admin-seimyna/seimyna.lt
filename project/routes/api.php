<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {
    Route::post('signup', [\App\Http\Controllers\Api\Auth\SignupController::class, 'post'])->name('signup');
});


Route::group(['middleware' => 'auth:api'], function() {
    Route::group(['prefix' => 'verify', 'as' => 'verify.'], function() {
        Route::post('{type}/{not_verified_verification:token}', [\App\Http\Controllers\Api\VerificationController::class, 'post'])->name('post');
    });
});
