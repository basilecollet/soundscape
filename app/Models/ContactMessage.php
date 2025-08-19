<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TFactory of ContactMessageFactory
 *
 * @implements HasFactory<TFactory>
 */
class ContactMessage extends Model
{
    /** @use HasFactory<TFactory> */
    use HasFactory;

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
     *
     * @param  Builder<ContactMessage>  $query
     * @return Builder<ContactMessage>
     */
    public function scopeUnread($query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read messages
     *
     * @param  Builder<ContactMessage>  $query
     * @return Builder<ContactMessage>
     */
    public function scopeRead($query): Builder
    {
        return $query->whereNotNull('read_at');
    }
}
