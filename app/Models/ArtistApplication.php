<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'reply',
        'replied_at',
        'internal_notes',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function isReplied(): bool
    {
        return $this->status === 'replied';
    }
}
