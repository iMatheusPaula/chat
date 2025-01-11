<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])
    ->name('auth.login')->middleware('web');

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])
    ->name('auth.logout')->middleware(['web','auth:sanctum']);

Route::post('/register', [UserController::class, 'store'])
    ->name('user.store')->middleware('web');

Route::group(['prefix' => 'contact', 'middleware' => ['web','auth:sanctum']], function () {
    Route::post('/store', [ContactController::class, 'store'])
        ->name('contact.store');

    Route::get('/index', [ContactController::class, 'index'])
        ->name('contact.index');

    Route::get('/show/{id}', [ContactController::class, 'show'])
        ->name('contact.show');

    Route::delete('/{id}', [ContactController::class, 'destroy'])
        ->name('contact.destroy');

    Route::post('/update/{id}', [ContactController::class, 'update'])
        ->name('contact.update');
});

Route::group(['prefix' => 'user', 'middleware' => ['web','auth:sanctum']], function () {
    Route::get('/', [UserController::class, 'show'])
        ->name('user.show')->middleware(['web','auth:sanctum']);

    Route::get('/search', [UserController::class, 'search'])
        ->name('user.search');

    Route::get('/{id}', [UserController::class, 'show'])
        ->name('user.show');

    Route::delete('/{id}', [UserController::class, 'destroy'])
        ->name('user.destroy');

    Route::patch('/', [UserController::class, 'update'])
        ->name('user.update');

});
