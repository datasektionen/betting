<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BettingController;
use Illuminate\Support\Facades\Route;

Route::get('/logout', [AuthController::class, 'getLogout'])->middleware('auth');
Route::get('/login', [AuthController::class, 'getLogin']);
Route::get('/login-complete', [AuthController::class, 'getLoginComplete']);

Route::get('/sm/end/', [BettingController::class, 'end']);
Route::get('/sm/day/{day}', [BettingController::class, 'day']);
Route::get('/sm/create/{name}', [BettingController::class, 'create']);
Route::get('/sm/live', [BettingController::class, 'live']);

Route::get('/', [BettingController::class, 'index']);
Route::post('/', [BettingController::class, 'bet'])->middleware('auth');
