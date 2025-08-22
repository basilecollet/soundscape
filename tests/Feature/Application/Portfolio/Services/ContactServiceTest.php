<?php

use App\Application\Portfolio\DTOs\ContactFormData;
use App\Application\Portfolio\Services\ContactService;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can save a contact message', function () {
    // Arrange
    $service = app(ContactService::class); // Use dependency injection
    $data = new ContactFormData(
        name: 'John Doe',
        email: 'john@example.com',
        subject: 'Test Subject',
        message: 'This is a test message',
        gdprConsent: true
    );

    // Act
    $message = $service->saveMessage($data);

    // Assert
    expect($message)->toBeInstanceOf(ContactMessage::class)
        ->and($message->name)->toBe('John Doe')
        ->and($message->email)->toBe('john@example.com')
        ->and($message->subject)->toBe('Test Subject')
        ->and($message->message)->toBe('This is a test message')
        ->and($message->gdpr_consent)->toBeTrue();

    $this->assertDatabaseHas('contact_messages', [
        'email' => 'john@example.com',
        'name' => 'John Doe',
    ]);
});
