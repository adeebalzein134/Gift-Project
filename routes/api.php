<?php

use App\Http\Controllers\GiftController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource("gifts", GiftController::class);

Route::post("register", [UserController::class, "register"]);
