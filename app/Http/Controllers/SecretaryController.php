<?php

namespace App\Http\Controllers;

use App\Exceptions\TokenMismatch;
use App\Models\Game;
use App\Models\LocalTeam;
use App\Models\Secretary;
use Illuminate\Http\Request;

class SecretaryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function insert(string $name, string $email)
    {
        try
        {
            $secretary = new Secretary();
            $secretary->name = $name;
            $secretary->email = $email;

            $secretary->save();
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

            $secretary = new Secretary();

            $secretary->name = $validatedData['name'];
//            $secretary->email = $validatedData['email'];
            $secretary->gameId = $validatedData['gameId'];

            $secretary->save();

            return response()->json([
                'message' => 'Secrétaire enregistré avec succès',
                'data' => $secretary
            ], 201);
        }
        catch (\Throwable $e)
        {
            return response()->json(['message' => 'Erreur lors de l\'enregistrement du secrétaire'], 404);
        }
    }
}
