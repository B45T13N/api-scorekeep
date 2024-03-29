<?php

namespace App\Http\Resources;

use App\Models\LocalTeam;
use App\Models\VisitorTeam;
use App\Models\Volunteer;
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
            'uuid' => $this->uuid,
            'address' => $this->address,
            'category' => $this->category,
            'gameDate' => $this->gameDate,
            'isHomeMatch' => $this->isHomeMatch,
            'isCancelled' => $this->isCancelled,
            'cancelledDate' => $this->cancelledDate,
            'timekeeper' => null,
            'secretary' => null,
            'roomManager' => null,
            'drinkManager' => null,
            'visitorTeam' => null,
        ];

        if ($this->secretaryId && Volunteer::query()->where('uuid', $this->secretaryId)->where('volunteerTypeId', "=", 1)->exists())
        {
            $data['secretary'] = new VolunteerResource(Volunteer::query()->find($this->secretaryId));
        }

        if ($this->timekeeperId && Volunteer::query()->where('uuid', $this->timekeeperId)->where('volunteerTypeId', "=", 2)->exists())
        {
            $data['timekeeper'] = new VolunteerResource(Volunteer::query()->find($this->timekeeperId));
        }

        if ($this->roomManagerId && Volunteer::query()->where('uuid', $this->roomManagerId)->where('volunteerTypeId', "=", 3)->exists())
        {
            $data['roomManager'] = new VolunteerResource(Volunteer::query()->find($this->roomManagerId));
        }

        if ($this->drinkManagerId && Volunteer::query()->where('uuid', $this->drinkManagerId)->where('volunteerTypeId', "=", 4)->exists())
        {
            $data['drinkManager'] = new VolunteerResource(Volunteer::query()->find($this->drinkManagerId));
        }

        if ($this->visitorTeamId && VisitorTeam::query()->where('uuid', $this->visitorTeamId)->exists())
        {
            $data['visitorTeam'] = new VisitorTeamResource(VisitorTeam::query()->find($this->visitorTeamId));
        }

        if ($this->localTeamId && LocalTeam::query()->where('uuid', $this->localTeamId)->exists())
        {
            $data['localTeam'] = new LocalTeamResource(LocalTeam::query()->find($this->localTeamId));
        }

        $data['secretaries'] = VolunteerResource::collection(Volunteer::query()->where('gameId', "=", $this->uuid)->where('volunteerTypeId', "=", 1)->get());
        $data['timekeepers'] = VolunteerResource::collection(Volunteer::query()->where('gameId', "=", $this->uuid)->where('volunteerTypeId', "=", 2)->get());
        $data['roomManagers'] = VolunteerResource::collection(Volunteer::query()->where('gameId', "=", $this->uuid)->where('volunteerTypeId', "=", 3)->get());
        $data['drinkManagers'] = VolunteerResource::collection(Volunteer::query()->where('gameId', "=", $this->uuid)->where('volunteerTypeId', "=", 4)->get());

        return $data;
    }
}
