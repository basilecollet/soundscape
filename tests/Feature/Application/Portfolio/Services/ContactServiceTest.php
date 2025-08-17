<?php

use App\Application\Portfolio\DTOs\ContactFormData;
use App\Application\Portfolio\Services\ContactService;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can save a contact message', function () {
    // Arrange
    $service = new ContactService;
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

test('it can get unread messages count', function () {
    // Arrange
    ContactMessage::create([
        'name' => 'User 1',
        'email' => 'user1@example.com',
        'message' => 'Message 1',
        'gdpr_consent' => true,
        'read_at' => null,
    ]);

    ContactMessage::create([
        'name' => 'User 2',
        'email' => 'user2@example.com',
        'message' => 'Message 2',
        'gdpr_consent' => true,
        'read_at' => now(),
    ]);

    ContactMessage::create([
        'name' => 'User 3',
        'email' => 'user3@example.com',
        'message' => 'Message 3',
        'gdpr_consent' => true,
        'read_at' => null,
    ]);

    $service = new ContactService;

    // Act
    $count = $service->getUnreadCount();

    // Assert
    expect($count)->toBe(2);
});

test('it can mark a message as read', function () {
    // Arrange
    $message = ContactMessage::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Test message',
        'gdpr_consent' => true,
        'read_at' => null,
    ]);

    $service = new ContactService;

    // Act
    $result = $service->markAsRead($message->id);

    // Assert
    expect($result)->toBeTrue();

    $message->refresh();
    expect($message->read_at)->not->toBeNull()
        ->and($message->isRead())->toBeTrue();
});

test('it returns false when marking non-existent message as read', function () {
    // Arrange
    $service = new ContactService;

    // Act
    $result = $service->markAsRead(999);

    // Assert
    expect($result)->toBeFalse();
});

test('it can get all messages with pagination', function () {
    // Arrange
    for ($i = 1; $i <= 20; $i++) {
        ContactMessage::create([
            'name' => "User {$i}",
            'email' => "user{$i}@example.com",
            'message' => "Message {$i}",
            'gdpr_consent' => true,
        ]);
    }

    $service = new ContactService;

    // Act
    $messages = $service->getAllMessages(10);

    // Assert
    expect($messages)->toHaveCount(10)
        ->and($messages->total())->toBe(20)
        ->and($messages->perPage())->toBe(10);
});
