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
        $data = [
            'id' => $this->id,
            'address' => $this->address,
            'category' => $this->category,
            'gameDate' => $this->gameDate,
            'timekeeper' => null,
            'secretary' => null,
            'room_manager' => null,
            'visitorTeam' => null,
        ];

        if ($this->timekeeperId && Timekeeper::query()->where('id', $this->timekeeperId)->exists()) {
            $data['timekeeper'] = new TimekeeperResource(Timekeeper::query()->find($this->timekeeperId));
        }

        if ($this->secretaryId && Secretary::query()->where('id', $this->secretaryId)->exists()) {
            $data['secretary'] = new SecretaryResource(Secretary::query()->find($this->secretaryId));
        }

        if ($this->roomManagerId && RoomManager::query()->where('id', $this->roomManagerId)->exists()) {
            $data['room_manager'] = new RoomManagerResource(RoomManager::query()->find($this->roomManagerId));
        }

        if ($this->visitorTeamId && VisitorTeam::query()->where('id', $this->visitorTeamId)->exists()) {
            $data['visitorTeam'] = new VisitorTeamResource(VisitorTeam::query()->find($this->visitorTeamId));
        }

        return $data;
    }
}
