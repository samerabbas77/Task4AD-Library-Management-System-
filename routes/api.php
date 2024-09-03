<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Ath\AuthController;
use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\BorrowController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\CategoryController;

//Login and Register route Dos not need Authenticaton
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

 
//Routesfor Auth users only
Route::group(['middleware' => 'api',
], function ($router) {
    //Api.................
    Route::apiResource('/user', UserController::class);
    Route::apiresource('roles', RoleController::class);

    Route::apiResource('/book',BooksController::class);

    Route::apiResource('/borrow',BorrowController::class);
    //Renew the boorow for the user
    Route::put('/borrow-renew/{borrow}',[BorrowController::class,'renew']);

    Route::apiResource('/category',CategoryController::class);

    Route::apiResource('/rating',RatingController::class);
    Route::get('/rate-avr/{book}',[RatingController::class,'getAVRrating']);


    //Auth...............................................
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});
