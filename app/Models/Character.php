<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Character extends Model
{
    protected $fillable = [
        'fullName',
        'nickname',
        'interpretedBy',
        'children',
        'image',
        'birthdate',
        'house_id',
        'api_index',
        'last_synced_at'
    ];

    
    protected $casts = [
        'children' => 'array',
        'last_synced_at' => 'datetime'
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function spells(): BelongsToMany
    {
        return $this->belongsToMany(Spell::class)
            ->withTimestamps()
            ->withPivot('last_synced_at');
    }

    /** @use HasFactory<\Database\Factories\CharacterFactory> */
    use HasFactory;
}
