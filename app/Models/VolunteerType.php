<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteerType extends Model
{
    use HasFactory;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
    ];

    /**
     * Get the volunteerTypes that owns the volunteer.
     */
    public function volunteers(): HasMany
    {
        return $this->hasMany(Volunteer::class);
    }
}
