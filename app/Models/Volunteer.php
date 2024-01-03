<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Volunteer extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'encrypted',
        'email' => 'encrypted',
    ];

    /**
     * Get the game that owns the volunteer.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }


    /**
     * Get the volunteerType that owns the volunteer.
     */
    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(VolunteerType::class);
    }
}
