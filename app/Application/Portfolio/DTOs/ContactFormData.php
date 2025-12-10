<?php

declare(strict_types=1);

namespace App\Application\Portfolio\DTOs;

class ContactFormData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $subject,
        public readonly string $message,
        public readonly bool $gdprConsent = false
    ) {}

    /**
     * Create from array (useful for form requests)
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            subject: $data['subject'] ?? null,
            message: $data['message'],
            gdprConsent: $data['gdpr_consent'] ?? false
        );
    }

    /**
     * Convert to array
     *
     * @return array{name: string, email: string, subject: ?string, message: string, gdpr_consent: bool}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'gdpr_consent' => $this->gdprConsent,
        ];
    }
}
