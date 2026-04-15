<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\DisciplineOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtistApplication extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'discipline',
        'experience',
        'bio',
        'availability',
        'status',
        'converted_artist_id',
        'approved_at',
        'converted_at',
        'reply',
        'replied_at',
        'internal_notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'converted_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function isReplied(): bool
    {
        return $this->replied_at !== null;
    }

    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'converted'], true);
    }

    public function isConverted(): bool
    {
        return $this->status === 'converted' || $this->converted_at !== null || $this->converted_artist_id !== null;
    }

    public function convertedArtist(): BelongsTo
    {
        return $this->belongsTo(Artist::class, 'converted_artist_id');
    }

    public function disciplineLabel(): string
    {
        return DisciplineOptions::label($this->discipline);
    }
}
