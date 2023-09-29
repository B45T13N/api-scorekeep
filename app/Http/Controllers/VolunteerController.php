<?php

namespace App\Http\Controllers;

use App\Exceptions\TokenMismatch;
use App\Models\Game;
use App\Models\LocalTeam;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function insert(string $name, string $email, int $volunteerTypeId)
    {
        try
        {
            $volunteer = new Volunteer();
            $volunteer->name = $name;
            $volunteer->email = $email;
            $volunteer->volunteerTypeId = $volunteerTypeId;

            $volunteer->save();
        }
        catch (\Throwable $e)
        {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
//            'email' => 'required|email',
            'volunteerTypeId' => 'required|int',
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

            $volunteer = new Volunteer();

            $volunteer->name = $validatedData['name'];
//            $volunteer->email = $validatedData['email'];
            $volunteer->gameId = $validatedData['gameId'];
            $volunteer->volunteerTypeId = $validatedData['volunteerTypeId'];

            $volunteer->save();

            return response()->json([
                'message' => 'Bénévole enregistré avec succès',
                'data' => $volunteer
            ], 201);
        }
        catch (\Throwable $e)
        {
            return response()->json(['message' => 'Erreur lors de l\'enregistrement du bénévole'], 404);
        }
    }
}
