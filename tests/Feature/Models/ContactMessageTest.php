<?php

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can create a contact message', function () {
    // Act
    $message = ContactMessage::create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'subject' => 'Inquiry',
        'message' => 'I would like to know more about your services',
        'gdpr_consent' => true,
    ]);

    // Assert
    expect($message)->toBeInstanceOf(ContactMessage::class)
        ->and($message->name)->toBe('Jane Doe')
        ->and($message->email)->toBe('jane@example.com')
        ->and($message->subject)->toBe('Inquiry')
        ->and($message->gdpr_consent)->toBeTrue()
        ->and($message->read_at)->toBeNull();
});

test('it can mark a message as read', function () {
    // Arrange
    $message = ContactMessage::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'message' => 'Test message',
        'gdpr_consent' => true,
    ]);

    expect($message->isRead())->toBeFalse();

    // Act
    $message->markAsRead();

    // Assert
    expect($message->isRead())->toBeTrue()
        ->and($message->read_at)->not->toBeNull();
});

test('marking as read twice does not change read_at', function () {
    // Arrange
    $message = ContactMessage::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'message' => 'Test message',
        'gdpr_consent' => true,
    ]);

    // Act
    $message->markAsRead();
    $firstReadAt = $message->read_at;

    // Travel 1 hour in the future
    $this->travelTo(now()->addHour());
    $message->markAsRead();

    // Assert
    expect($message->read_at->timestamp)->toBe($firstReadAt->timestamp);
});

test('scope unread returns only unread messages', function () {
    // Arrange
    ContactMessage::create([
        'name' => 'Unread 1',
        'email' => 'unread1@example.com',
        'message' => 'Message',
        'gdpr_consent' => true,
        'read_at' => null,
    ]);

    ContactMessage::create([
        'name' => 'Read',
        'email' => 'read@example.com',
        'message' => 'Message',
        'gdpr_consent' => true,
        'read_at' => now(),
    ]);

    ContactMessage::create([
        'name' => 'Unread 2',
        'email' => 'unread2@example.com',
        'message' => 'Message',
        'gdpr_consent' => true,
        'read_at' => null,
    ]);

    // Act
    $unreadMessages = ContactMessage::unread()->get();

    // Assert
    expect($unreadMessages)->toHaveCount(2)
        ->and($unreadMessages->pluck('name')->toArray())->toBe(['Unread 1', 'Unread 2']);
});

test('scope read returns only read messages', function () {
    // Arrange
    ContactMessage::create([
        'name' => 'Unread',
        'email' => 'unread@example.com',
        'message' => 'Message',
        'gdpr_consent' => true,
        'read_at' => null,
    ]);

    ContactMessage::create([
        'name' => 'Read 1',
        'email' => 'read1@example.com',
        'message' => 'Message',
        'gdpr_consent' => true,
        'read_at' => now(),
    ]);

    ContactMessage::create([
        'name' => 'Read 2',
        'email' => 'read2@example.com',
        'message' => 'Message',
        'gdpr_consent' => true,
        'read_at' => now(),
    ]);

    // Act
    $readMessages = ContactMessage::read()->get();

    // Assert
    expect($readMessages)->toHaveCount(2)
        ->and($readMessages->pluck('name')->toArray())->toContain('Read 1', 'Read 2');
});
