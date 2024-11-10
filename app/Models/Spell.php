<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Spell extends Model
{
    protected $fillable = [
        'spell',
        'use',
        'api_index',
        'last_synced_at'
    ];

    protected $casts = [
        'last_synced_at' => 'datetime'
    ];

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)
            ->withTimestamps()
            ->withPivot('last_synced_at');
    }

    /** @use HasFactory<\Database\Factories\SpellFactory> */
    use HasFactory;
}
