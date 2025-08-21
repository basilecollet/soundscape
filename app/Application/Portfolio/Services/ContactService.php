<?php

namespace App\Application\Portfolio\Services;

use App\Application\Portfolio\DTOs\ContactFormData;
use App\Domain\Admin\Repositories\ContactRepository;
use App\Models\ContactMessage;

class ContactService
{
    public function __construct(
        private readonly ContactRepository $contactRepository
    ) {}

    /**
     * Save a contact message to the database
     */
    public function saveMessage(ContactFormData $data): ContactMessage
    {
        return $this->contactRepository->create([
            'name' => $data->name,
            'email' => $data->email,
            'subject' => $data->subject,
            'message' => $data->message,
            'gdpr_consent' => $data->gdprConsent,
        ]);
    }
}
