<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BettingController;
use Illuminate\Support\Facades\Route;

Route::get('/logout', [AuthController::class, 'getLogout'])->middleware('auth');
Route::get('/login', [AuthController::class, 'getLogin']);
Route::get('/login-complete', [AuthController::class, 'getLoginComplete']);

Route::post('/sm/end/', [BettingController::class, 'end']);  // Kanske add AdminCheck here?
Route::post('/sm/day/{day}', [BettingController::class, 'day']);
Route::post('/sm/create', [BettingController::class, 'create']);
Route::post('/sm/live', [BettingController::class, 'live']);

Route::get('/sm/end/', [BettingController::class, 'skurk']);
Route::get('/sm/day/{day}', [BettingController::class, 'skurk']);
Route::get('/sm/create/{name}', [BettingController::class, 'skurk']);
Route::get('/sm/live', [BettingController::class, 'skurk']);

Route::get('/', [BettingController::class, 'index']);
Route::post('/', [BettingController::class, 'bet'])->middleware('auth');
Route::get('/admin', [BettingController::class, 'getAdmin']);
