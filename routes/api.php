<?php

use App\Http\Controllers\AjouteController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dahboard;
use App\Http\Controllers\EntresortiController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\globale;
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
Route::post('articles/delect', [ArticleController::class, 'removeAll']);
Route::post('articles/stocks/{id}', [ArticleController::class,"stocks"]);
Route::get('factures/download/{id}', [FactureController::class,"download"]);
Route::get('historique/add', [globale::class,"addStocks"]);
Route::get('historique/remove', [globale::class,"removeStocks"]);
Route::get('historique/prix', [globale::class,"prixStocks"]);
Route::get('historique/delect', [globale::class,"delectStocks"]);
Route::get('dahboard', [Dahboard::class,"TotalArticle"]);
Route::get('entresorties/retirer', [EntresortiController::class,"retirer"]);
Route::get('entresorties/ajouter', [EntresortiController::class,"ajouter"]);

Route::middleware('api')->group(function () {
    Route::resource('articles', ArticleController::class);
    Route::resource('ajoutes', AjouteController::class);
    Route::resource('ventes', VenteController::class);
    Route::resource('factures', FactureController::class);
    Route::resource('entresorties', EntresortiController::class);
});
