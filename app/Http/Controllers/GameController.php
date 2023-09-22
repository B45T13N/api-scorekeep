<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rules = [
            'local_team_id' => 'int|required',
            'start_date' => 'date|required|after_or_equal:today',
            'end_date' => 'date|required|after_or_equal:today',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = [
                'status' => '0',
                'error' => $validator->errors(),
            ];

            return response()->json(['validator_failed' => $response], 401);
        }
        $localTeamId = $request->get('local_team_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $perPage = $request->input('per_page', 10);

        $query = Game::query();

        if ($localTeamId) {
            $query->where('localTeamId', $localTeamId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('gameDate', [$startDate, $endDate]);
        }

        $query->where('isDeleted', '=', false);

        $games = $query->orderBy('gameDate')->paginate($perPage);

        return GameResource::collection($games);
    }

    /**
     * Display a listing of the resource.
     */
    public function weekGames(Request $request)
    {
        $rules = [
            'local_team_id' => 'int|required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = [
                'status' => '0',
                'error' => $validator->errors(),
            ];

            return response()->json(['validator_failed' => $response], 401);
        }
        $localTeamId = $request->get('local_team_id');

        $perPage = $request->input('per_page', 10);

        $query = Game::query();

        if ($localTeamId) {
            $query->where('localTeamId', $localTeamId);
        }
        $startDate = Carbon::now("Europe/Paris")->format('Y-m-d');
        $endDate = Carbon::now("Europe/Paris")->addWeek()->format('Y-m-d');

        $query->whereBetween('gameDate', [$startDate, $endDate]);

        $query->where('isDeleted', '=', false);

        $games = $query->paginate($perPage);

        return GameResource::collection($games);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, VisitorTeamController $visitorTeamController)
    {
        $validatedData = $request->validate([
            'localTeamId' => 'required|exists:local_teams,id',
            'address' => 'required|string',
            'category' => 'required|string',
            'visitorTeamName' => 'required|string',
            'isHomeMatch' => 'required|boolean',
            'gameDate' =>
                [
                    'required',
                    'date',
                    'after:'.Carbon::now('Europe/Paris')->toDateTimeString(),
                ],
        ]);

        $game = new Game();
        $game->localTeamId = $validatedData['localTeamId'];
        $game->address = $validatedData['address'];
        $game->category = $validatedData['category'];
        $game->gameDate = $validatedData['gameDate'];
        $game->isHomeMatch = $validatedData['isHomeMatch'];
        $this->checkForeignKeys($request, $game);

        $visitorTeam = $visitorTeamController->store($request['visitorTeamName']);

        $game->visitorTeamId = $visitorTeam->id;

        $game->save();


        return response()->json(['message' => 'Match enregistré avec succès', 'data' => $game->gameDate], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $gameId)
    {
        try
        {
            $game = Game::query()->where("id", "=", $gameId)->first();

            if($game === null)
            {
                throw new ModelNotFoundException();
            }

            return new GameResource($game);
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
            $game = Game::query()->findOrFail($gameId)->first();

            $validatedData = $request->validate([
                'timekeeperId' => 'nullable|exists:timekeepers,id',
                'secretaryId' => 'nullable|exists:secretaries,id',
                'roomManagerId' => 'nullable|exists:room_managers,id',
                'address' => 'nullable|string',
                'gameDate' =>
                    [
                        'required',
                        'date',
                        'after:'.Carbon::now('Europe/Paris')->toDateTimeString(),
                    ],
            ]);

            $this->checkForeignKeys($request, $game);

            if ($request->has('gameDate'))
            {
                $game->gameDate = $validatedData['gameDate'];
            }
            if ($request->has('address'))
            {
                $game->address = $validatedData['address'];
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
     * Update the specified resource in storage.
     */
    public function addPerson(string $fieldName, int $fieldId, int $gameId)
    {
        try
        {
            $game = Game::query()->where("id", $gameId)->first();

            $game = $this->checkFieldUpdated($fieldName, $fieldId, $game);

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
     * Confirm the specified resource from storage.
     */
    public function confirm(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'gameId' => 'integer|exists:games,id',
            ]);

            $gameId = $validatedData['gameId'];

            $game = Game::query()->where("id", $gameId)->first();

            $game->isCancelled = false;
            $game->cancelledDate = null;

            $game->save();

            return response()->json(['message' => 'Match confirmé avec succès'], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'message' => 'Match non trouvé',
            ], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'gameId' => 'integer|exists:games,id',
            ]);

            $gameId = intval($validatedData['gameId']);

            $game = Game::query()->where("id", $gameId)->first();

            $game->isDeleted = true;
            $game->deletedDate = Carbon::now();

            $game->save();

            return response()->json(['message' => 'Match supprimé avec succès'], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'message' => 'Match non trouvé',
            ], 404);
        }

    }

    /**
     * Cancel the specified resource from storage.
     */
    public function cancel(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'gameId' => 'integer|exists:games,id',
            ]);

            $gameId = $validatedData['gameId'];

            $game = Game::query()->where("id", $gameId)->first();

            $game->isCancelled = true;
            $game->cancelledDate = Carbon::now();

            $game->save();

            return response()->json(['message' => 'Match annulé avec succès'], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'message' => 'Match non trouvé',
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

    private function checkFieldUpdated(string $fieldName, int $fieldId, Game $game) : Game
    {
        switch ($fieldName)
        {
            case 'timekeeper':
                $game->timekeeperId = $fieldId;
                break;
            case 'roomManager':
                $game->roomManagerId = $fieldId;
                break;
            case 'secretary':
                $game->secretaryId = $fieldId;
                break;
        }

        return $game;
    }
}
