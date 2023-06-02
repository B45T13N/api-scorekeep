<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GameResource::collection(Game::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, VisitorTeamController $visitorTeamController)
    {
        $validatedData = $request->validate([
            'address' => 'required|string',
            'category' => 'required|string',
            'visitorTeam' => 'required|string',
            'gameDate' =>
                [
                    'required',
                    'date',
                    'after:'.Carbon::now('Europe/Paris')->toDateTimeString(),
                ],
        ]);

        $game = new Game();

        $game->address = $validatedData['address'];
        $game->category = $validatedData['category'];
        $game->gameDate = $validatedData['gameDate'];

        $this->checkForeignKeys($request, $game);

        $game->save();

        $visitorTeamController->store($request['visitorTeam'], $game->id);

        return response()->json(['message' => 'Match enregistré avec succès'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $gameId)
    {
        try
        {
            return new GameResource(Game::query()->findOrFail($gameId)->get());
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'message' => 'Match non trouvé',
                'exception' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $gameId)
    {
        try
        {
            $game = Game::query()->findOrFail($gameId)->get();

            $validatedData = $request->validate([
                'timekeeperId' => 'nullable,id',
                'secretaryId' => 'nullable,id',
                'roomManagerId' => 'nullable,id',
                'gameDate' => 'nullable|date',
            ]);

            $this->checkForeignKeys($request, $game);

            if ($request->has('gameDate')) {
                $game->gameDate = $validatedData['gameDate'];
            }

            $game->save();

            // Réponse de succès
            return response()->json(['message' => 'Match mis à jour avec succès'], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                    'message' => 'Match non trouvé',
                    'exception' => $e->getMessage()
                ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $gameId)
    {
        try {
            $game = Game::findOrFail($gameId);

            $game->delete();

            return response()->json(['message' => 'Match supprimé avec succès'], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'message' => 'Match non trouvé',
                'exception' => $e->getMessage()
            ], 404);
        }

    }

    private function checkForeignKeys(Request $request, Game $game): Game
    {
        if ($request->has('timekeeperId'))
        {
            $game->timekeeperId = $request->input('timekeeperId');
        }
        if ($request->has('secretaryId'))
        {
            $game->secretaryId = $request->input('secretaryId');
        }
        if ($request->has('roomManagerId'))
        {
            $game->roomManagerId = $request->input('roomManagerId');
        }

        return $game;
    }
}
