<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\LocalTeamController;
use App\Http\Controllers\RoomManagerController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\TimekeeperController;
use App\Http\Controllers\VisitorTeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login'])->name('api.login');
//Route::post('/register', [\App\Http\Controllers\Api\RegisterController::class, 'register']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('api.logout');
    Route::post('/me', [App\Http\Controllers\LoginController::class, 'me'])->name('api.me');
});
Route::middleware('api.public_key')->group(function () {

    Route::get('games', [GameController::class, 'index'])->name('games.index');
    Route::get('weekGames', [GameController::class, 'weekGames'])->name('games.weekGames');
    Route::get('games/{gameId}', [GameController::class, 'show'])->name('games.show');

    Route::put('games/{gameId}', [GameController::class, 'update'])->name('games.update');

    Route::post('games', [GameController::class, 'store'])->name('games.store');
    Route::post('games/confirm', [GameController::class, 'confirm'])->name('games.confirm');
    Route::post('games/cancel', [GameController::class, 'cancel'])->name('games.cancel');
    Route::post('games/delete', [GameController::class, 'delete'])->name('games.delete');

    Route::get('/visitor-teams/{visitorTeamId}', [VisitorTeamController::class, 'show'])->name('visitor_teams.show');

    Route::put('/visitor-teams/{visitorTeamId}', [VisitorTeamController::class, 'update'])->name('visitor_teams.update');

    Route::get('/local-teams', [LocalTeamController::class, 'index'])->name('local_teams.index');

    Route::post('/local-teams/store', [LocalTeamController::class, 'store'])->name('local_teams.store');

    Route::get('/local-teams/{localTeamId}', [LocalTeamController::class, 'show'])->name('local_teams.show');

    Route::put('/local-teams/{localTeamId}', [LocalTeamController::class, 'update'])->name('local_teams.update');


    Route::post('room-managers/store', [RoomManagerController::class, 'store'])->name('room_managers.store');

    Route::post('secretaries/store', [SecretaryController::class, 'store'])->name('secretaries.store');

    Route::post('timekeepers/store', [TimekeeperController::class, 'store'])->name('timekeepers.store');
});


