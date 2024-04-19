<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Character extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'enemy',
        'defence',
        'strength',
        'accuracy',
        'magic',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contests(): BelongsToMany
    {
        if ($this->enemy) {
            return $this->belongsToMany(Contest::class, 'character_contest', 'enemy_id');
        }
        return $this->belongsToMany(Contest::class);
    }
}
