<?php

namespace App\Http\Controllers;

use App\Exceptions\TokenMismatch;
use App\Models\Game;
use App\Models\LocalTeam;
use App\Models\RoomManager;
use Illuminate\Http\Request;

class RoomManagerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function insert(string $name, string $email)
    {
        try
        {
            $roomManager = new RoomManager();
            $roomManager->name = $name;
            $roomManager->email = $email;

            $roomManager->save();
        }
        catch (\Throwable $e)
        {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, GameController $gameController)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
//            'email' => 'required|email',
            'gameId' => 'required|int',
            'token' => 'required|int'
        ]);

        try
        {
            $game = Game::findOrFail($validatedData['gameId']);
            $token = LocalTeam::findOrFail($game->localTeamId)->token;

            if ($validatedData['token'] != $token)
            {
                throw new TokenMismatch();
            }

            $roomManager = new RoomManager();

            $roomManager->name = $validatedData['name'];
//            $roomManager->email = $validatedData['email'];
            $roomManager->gameId = $validatedData['gameId'];

            $roomManager->save();

            return response()->json([
                'message' => 'Responsable de salle enregistré avec succès',
                'data' => $roomManager
            ], 201);
        }
        catch (\Throwable $e)
        {
            return response()->json(['message' => 'Erreur lors de l\'enregistrement du responsable de salle'], 404);
        }
    }
}
