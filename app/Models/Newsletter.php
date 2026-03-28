<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasUlids;

    protected $fillable = [
        'subject',
        'body',
        'recipient_type',
        'recipient_ids',
        'status',
        'sent_at',
        'total_sent',
    ];

    protected $casts = [
        'recipient_ids' => 'array',
        'sent_at' => 'datetime',
        'total_sent' => 'integer',
    ];

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', 'sent');
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isSending(): bool
    {
        return $this->status === 'sending';
    }

    public function getRecipientCount(): int
    {
        if ($this->recipient_type === 'custom' && is_array($this->recipient_ids)) {
            return count($this->recipient_ids);
        }

        return NewsletterSubscriber::active()->confirmed()->count();
    }
}
