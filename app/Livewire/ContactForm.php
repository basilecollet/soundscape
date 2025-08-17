<?php

namespace App\Livewire;

use App\Application\Portfolio\DTOs\ContactFormData;
use App\Application\Portfolio\Services\ContactService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:255')]
    public string $subject = '';

    #[Validate('required|string|max:2000')]
    public string $message = '';

    #[Validate('accepted')]
    public bool $gdpr_consent = false;

    public bool $submitted = false;

    public function submit(ContactService $contactService): void
    {
        $this->validate();

        $contactData = new ContactFormData(
            name: $this->name,
            email: $this->email,
            subject: $this->subject ?: null,
            message: $this->message,
            gdprConsent: $this->gdpr_consent
        );

        $contactService->saveMessage($contactData);

        $this->reset(['name', 'email', 'subject', 'message', 'gdpr_consent']);
        $this->submitted = true;

        session()->flash('success', 'Thank you for your message! We will get back to you soon.');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}