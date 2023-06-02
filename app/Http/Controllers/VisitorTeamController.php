<?php

namespace App\Http\Controllers;

use App\Http\Resources\VisitorTeamResource;
use App\Models\VisitorTeam;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class VisitorTeamController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $visitorTeamName, int $gameId)
    {
        $visitorTeam = new VisitorTeam();
        $visitorTeam->name = $visitorTeamName;
        $visitorTeam->gameId = $gameId;

        $visitorTeam->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $visitorTeamId)
    {
        try
        {
            return new VisitorTeamResource(VisitorTeam::query()->findOrFail($visitorTeamId)->first());
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'message' => 'Equipe visiteur non trouvée',
                'exception' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $visitorTeamId)
    {
        try
        {
            $visitorTeam = VisitorTeam::query()->findOrFail($visitorTeamId)->first();

            $validatedData = $request->validate([
                'name' => 'required|string',
            ]);

            $visitorTeam->name = $validatedData['name'];

            $visitorTeam->save();

            // Réponse de succès
            return response()->json(['message' => 'Equipe visiteur mise à jour avec succès'], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'message' => 'Equipe visiteur non trouvée',
                'exception' => $e->getMessage()
            ], 404);
        }
    }
}
