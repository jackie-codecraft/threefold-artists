<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discipline extends Model
{
    protected $fillable = [
        'slug',
        'name',
    ];

    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class)->withTimestamps();
    }
}
