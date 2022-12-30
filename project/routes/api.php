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
    Route::post('login', [\App\Http\Controllers\Api\Auth\LoginController::class, 'post'])->name('login');
    Route::post('invitation/{type}/accept', [\App\Http\Controllers\Api\Auth\InvitationController::class, 'accept'])->name('invitation.accept');
});


Route::group(['middleware' => 'auth:api'], function() {

    // Logout
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {
        Route::post('logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])->name('logout');
    });

    // Verifications
    Route::group(['prefix' => 'verify', 'as' => 'verify.'], function() {
        Route::get('{unverified:token}', [\App\Http\Controllers\Api\VerificationController::class, 'get'])->name('get');
        Route::post('{type}/{unverified:token}', [\App\Http\Controllers\Api\VerificationController::class, 'post'])->name('post');
        Route::post('{type}/{unverified:token}/resend', [\App\Http\Controllers\Api\VerificationController::class, 'resend'])->name('resend');
    });

    // Family
    Route::group(['prefix' => 'family', 'as' => 'family.'], function () {
        Route::post('create', [\App\Http\Controllers\Api\FamilyController::class, 'create'])->name('create');
    });

    // Family member
    Route::group(['prefix' => 'member', 'as' => 'member.'], function () {
        Route::post('create', [\App\Http\Controllers\Api\MemberController::class, 'create'])->name('create');
    });
});
