<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'is_read',
        'status',
        'internal_notes',
        'reply',
        'replied_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    public function isReplied(): bool
    {
        return $this->status === 'replied';
    }
}
