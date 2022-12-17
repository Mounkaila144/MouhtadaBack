<?php

use App\Http\Controllers\AjouteController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntresortiController;
use App\Http\Controllers\VenteController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

Route::middleware('api')->group(function () {
    Route::resource('articles', ArticleController::class);
});
Route::middleware('api')->group(function () {
    Route::resource('ajoutes', AjouteController::class);
});
Route::middleware('api')->group(function () {
    Route::resource('ventes', VenteController::class);
});
Route::middleware('api')->group(function () {
    Route::resource('entresorties', EntresortiController::class);
});
