<?php

namespace App\Http\Controllers;

use App\Models\Timekeeper;
use Illuminate\Http\Request;

class TimekeeperController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function insert(string $name, string $email)
    {
        try
        {
            $timekeeper = new Timekeeper();
            $timekeeper->name = $name;
            $timekeeper->email = $email;

            $timekeeper->save();
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
            'email' => 'required|email',
            'gameId' => 'required|int'
        ]);

        try
        {
            $timekeeper = new Timekeeper();

            $timekeeper->name = $validatedData['name'];
            $timekeeper->email = $validatedData['email'];
            $timekeeper->gameId = $validatedData['gameId'];

            $timekeeper->save();

            return response()->json(['message' => 'Chronométreur enregistré avec succès'], 201);
        }
        catch (\Throwable $e)
        {
            return response()->json(['message' => 'Erreur lors de l\'enregistrement du chronométreur'], 404);
        }
    }
}
