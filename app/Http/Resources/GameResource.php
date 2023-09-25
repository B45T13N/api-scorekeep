<?php

namespace App\Http\Resources;

use App\Models\LocalTeam;
use App\Models\RoomManager;
use App\Models\Secretary;
use App\Models\Timekeeper;
use App\Models\VisitorTeam;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'address' => $this->address,
            'category' => $this->category,
            'gameDate' => $this->gameDate,
            'isHomeMatch' => $this->isHomeMatch,
            'isCancelled' => $this->isCancelled,
            'cancelledDate' => $this->cancelledDate,
            'timekeeper' => null,
            'secretary' => null,
            'roomManager' => null,
            'visitorTeam' => null,
        ];

        if ($this->timekeeperId && Timekeeper::query()->where('id', $this->timekeeperId)->exists())
        {
            $data['timekeeper'] = new TimekeeperResource(Timekeeper::query()->find($this->timekeeperId));
        }

        if ($this->secretaryId && Secretary::query()->where('id', $this->secretaryId)->exists())
        {
            $data['secretary'] = new SecretaryResource(Secretary::query()->find($this->secretaryId));
        }

        if ($this->roomManagerId && RoomManager::query()->where('id', $this->roomManagerId)->exists())
        {
            $data['roomManager'] = new RoomManagerResource(RoomManager::query()->find($this->roomManagerId));
        }

        if ($this->visitorTeamId && VisitorTeam::query()->where('id', $this->visitorTeamId)->exists())
        {
            $data['visitorTeam'] = new VisitorTeamResource(VisitorTeam::query()->find($this->visitorTeamId));
        }

        if ($this->localTeamId && LocalTeam::query()->where('id', $this->localTeamId)->exists())
        {
            $data['localTeam'] = new LocalTeamResource(LocalTeam::query()->find($this->localTeamId));
        }

        $data['secretaries'] = SecretaryResource::collection(Secretary::query()->where('gameId', "=", $this->id)->get());
        $data['timekeepers'] = TimekeeperResource::collection(Timekeeper::query()->where('gameId', "=", $this->id)->get());
        $data['roomManagers'] = RoomManagerResource::collection(RoomManager::query()->where('gameId', "=", $this->id)->get());

        return $data;
    }
}
