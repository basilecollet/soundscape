<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'gdpr_consent',
        'read_at',
    ];

    protected $casts = [
        'gdpr_consent' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Mark the message as read
     */
    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Check if the message has been read
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read messages
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}
