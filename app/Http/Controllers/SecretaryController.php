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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        try
        {
            $secretary = new Secretary();

            $secretary->name = $validatedData['name'];
            $secretary->email = $validatedData['email'];

            $secretary->save();

            return response()->json(['message' => 'Secrétaire enregistré avec succès'], 201);
        }
        catch (\Throwable $e)
        {
            return response()->json(['message' => 'Erreur lors de l\'enregistrement du secrétaire'], 404);
        }
    }
}
