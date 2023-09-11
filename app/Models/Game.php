<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model
{
    use HasFactory;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address',
        'visitorTeamId',
        'timekeeperId',
        'secretaryId',
        'roomManagerId',
        'localTeamId',
        'isHomeMatch',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gameDate' => 'datetime',
    ];

    /**
     * Get the scorekeeper associated with the game.
     */
    public function localTeam(): BelongsTo
    {
        return $this->belongsTo(Timekeeper::class);
    }

    /**
     * Get the scorekeeper associated with the game.
     */
    public function timekeeper(): HasOne
    {
        return $this->hasOne(Timekeeper::class);
    }

    /**
     * Get the secretary associated with the game.
     */
    public function secretary(): HasOne
    {
        return $this->hasOne(Secretary::class);
    }

    /**
     * Get the roomManager associated with the game.
     */
    public function roomManager(): HasOne
    {
        return $this->hasOne(RoomManager::class);
    }

    /**
     * Get the visitorTeam associated with the game.
     */
    public function visitorTeam(): HasOne
    {
        return $this->hasOne(VisitorTeam::class);
    }

}
