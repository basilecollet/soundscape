<?php

namespace App\Application\Portfolio\Services;

use App\Application\Portfolio\DTOs\ContactFormData;
use App\Models\ContactMessage;

class ContactService
{
    /**
     * Save a contact message to the database
     */
    public function saveMessage(ContactFormData $data): ContactMessage
    {
        return ContactMessage::create([
            'name' => $data->name,
            'email' => $data->email,
            'subject' => $data->subject,
            'message' => $data->message,
            'gdpr_consent' => $data->gdprConsent,
        ]);
    }

    /**
     * Get unread messages count
     */
    public function getUnreadCount(): int
    {
        return ContactMessage::unread()->count();
    }

    /**
     * Get all messages with pagination
     */
    public function getAllMessages(int $perPage = 15)
    {
        return ContactMessage::latest()->paginate($perPage);
    }

    /**
     * Mark a message as read
     */
    public function markAsRead(int $messageId): bool
    {
        $message = ContactMessage::find($messageId);

        if ($message) {
            $message->markAsRead();

            return true;
        }

        return false;
    }
}
