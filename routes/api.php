<?php

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

Route::post('/poll', [App\Http\Controllers\PollController::class, 'store']);
Route::get('/poll/{id}', [App\Http\Controllers\PollController::class, 'show']);
Route::post('/poll/{id}/vote', [App\Http\Controllers\PollController::class, 'registerVote']);
Route::get('/poll/{id}/stats', [App\Http\Controllers\PollController::class, 'stats']);
