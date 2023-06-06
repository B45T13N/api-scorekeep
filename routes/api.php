<?php

use App\Http\Controllers\GameController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api.public_key')->group(function () {

    Route::get('games', [GameController::class, 'index'])->name('games.index');
    Route::get('games/{gameId}', [GameController::class, 'show'])->name('games.show');

    Route::put('games/{gameId}', [GameController::class, 'update'])->name('games.update');

    Route::post('games', [GameController::class, 'store'])->name('games.store');

    Route::get('/visitor-teams/{visitorTeamId}', [VisitorTeamController::class, 'show'])->name('visitor_teams.show');

    Route::put('/visitor-teams/{visitorTeamId}', [VisitorTeamController::class, 'update'])->name('visitor_teams.update');


    Route::post('room-managers/store', [RoomManagerController::class, 'store'])->name('room_managers.store');

    Route::post('secretaries/store', [SecretaryController::class, 'store'])->name('secretaries.store');

    Route::post('timekeepers/store', [TimekeeperController::class, 'store'])->name('timekeepers.store');
});


