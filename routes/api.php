<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\Dahboard;
use App\Http\Controllers\EntresortiController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\globale;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
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

Route::group(['middleware' => 'permission'], function () {    // Toutes les routes de ce groupe nécessitent le rôle d'administrateur
    Route::post('articles/delect', [ArticleController::class, 'removeAll']);
    Route::post('articles/stocks/{id}', [ArticleController::class,"stocks"]);
    Route::get('factures/download/{id}', [FactureController::class,"download"]);
    Route::get('historique/add', [globale::class,"addStocks"]);
    Route::get('historique/remove', [globale::class,"removeStocks"]);
    Route::get('historique/prix', [globale::class,"prixStocks"]);
    Route::get('historique/delect', [globale::class,"delectStocks"]);
    Route::get('dahboard', [Dahboard::class,"TotalArticle"]);
    Route::get('entresorties/retirer', [EntresortiController::class,"retirer"]);
    Route::resource('ventes', VenteController::class);
    Route::resource('articles', ArticleController::class);
    Route::resource('categories', CategorieController::class);
    Route::resource('factures', FactureController::class);
    Route::resource('users', UserController::class);
    Route::post('reservations/vente/{id}', [ReservationController::class,"vente"]);


});
Route::group(['middleware' => 'permissionUser'], function () {
    Route::get('entresorties/retirer', [EntresortiController::class,"retirer"]);
    Route::post('reservations/vente/{id}', [ReservationController::class,"vente"]);
    Route::get('dahboard', [Dahboard::class,"TotalArticle"]);
    Route::get('articles', [ArticleController::class,"index"]);
    Route::resource('ventes', VenteController::class);
    Route::get('categories', [CategorieController::class,"index"]);
    Route::resource('factures', FactureController::class);
    Route::get('historique/add', [globale::class,"addStocks"]);
    Route::get('historique/remove', [globale::class,"removeStocks"]);
    Route::get('historique/prix', [globale::class,"prixStocks"]);
    Route::get('historique/delect', [globale::class,"delectStocks"]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::get('/me', [AuthController::class, 'me']);
Route::post('/adduser', [AuthController::class, 'register']);
Route::resource('factures', FactureController::class);
Route::resource('reservations', ReservationController::class);
Route::post('reservations/payer/{id}', [ReservationController::class,"payer"]);
Route::resource('users', UserController::class);






