<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// ORM import Laravel
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasOne;        // 1:1
// use Illuminate\Database\Eloquent\Relations\HasMany;       // 1:n
// use Illuminate\Database\Eloquent\Relations\BelongsTo;     // n:1
// use Illuminate\Database\Eloquent\Relations\BelongsToMany; // n:m

class House extends Model
{
    protected $fillable = [
        'house',
        'emoji',
        'founder',
        'colors',
        'animal',
        'api_index',
        'last_synced_at'
    ];

    protected $casts = [
        'colors' => 'array',
        'last_synced_at' => 'datetime'
    ];

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    /** @use HasFactory<\Database\Factories\HouseFactory> */
    use HasFactory;
}
