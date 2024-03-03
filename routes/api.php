<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\LocalTeamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VisitorTeamController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\VolunteerTypeController;
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

//Route::post('/register', [\App\Http\Controllers\Api\RegisterController::class, 'register']);

Route::middleware('api.public_key')->group(function () {

    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::put('games/addVolunteers/{gameId}', [GameController::class, 'addVolunteers'])->name('games.addVolunteers');
        Route::put('games/{gameId}', [GameController::class, 'update'])->name('games.update');
        Route::post('games', [GameController::class, 'store'])->name('games.store');
        Route::post('games/confirm', [GameController::class, 'confirm'])->name('games.confirm');
        Route::post('games/cancel', [GameController::class, 'cancel'])->name('games.cancel');
        Route::post('games/delete', [GameController::class, 'delete'])->name('games.delete');

        Route::put('/visitor-teams/{visitorTeamId}', [VisitorTeamController::class, 'update'])->name('visitor_teams.update');
    });

    Route::get('games', [GameController::class, 'index'])->name('games.index');
    Route::get('weekGames', [GameController::class, 'weekGames'])->name('games.weekGames');
    Route::get('games/{gameId}', [GameController::class, 'show'])->name('games.show');

    Route::get('/visitor-teams/{visitorTeamId}', [VisitorTeamController::class, 'show'])->name('visitor_teams.show');

    Route::get('/local-teams', [LocalTeamController::class, 'index'])->name('local_teams.index');

    //Route::post('/local-teams/store', [LocalTeamController::class, 'store'])->name('local_teams.store');

    Route::get('/local-teams/{localTeamId}', [LocalTeamController::class, 'show'])->name('local_teams.show');

    //Route::put('/local-teams/{localTeamId}', [LocalTeamController::class, 'update'])->name('local_teams.update');

    Route::post('volunteers/store', [VolunteerController::class, 'store'])->name('volunteers.store');

    Route::get('volunteer-types/show/{volunteerTypeId}', [VolunteerTypeController::class, 'show'])->name('volunteers.show');
    Route::get('volunteer-types/show-all', [VolunteerTypeController::class, 'showAll'])->name('volunteers.showAll');

});
