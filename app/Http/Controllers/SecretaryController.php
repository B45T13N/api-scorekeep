<?php

namespace App\Http\Controllers;

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
            'email' => 'required|email',
            'gameId' => 'required|int'
        ]);

        try
        {
            $secretary = new Secretary();

            $secretary->name = $validatedData['name'];
            $secretary->email = $validatedData['email'];
            $secretary->gameId = $validatedData['gameId'];

            $secretary->save();

            $gameController->addPerson("secretary", $secretary->id, $validatedData['gameId']);

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
