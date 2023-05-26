<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'address' => $this->address,
            'category' => $this->category,
            'gameDate' => $this->gameDate,
            'timekeeper' => new TimekeeperResource(Timekeeper::query()->where('id', $this->timekeeper_id)->firstOrFail()),
            'secretary' => new SecretaryResource(Secretary::query()->where('id', $this->secretary_id)->firstOrFail()),
            'room_manager' => new RoomManagerResource(RoomManager::query()->where('id', $this->room_manager_id)->firstOrFail()),
            'visitorTeam' => new VisitorTeamResource(VisitorTeam::query()->where('id', $this->visitor_team_id)->firstOrFail()),
        ];
    }
}
