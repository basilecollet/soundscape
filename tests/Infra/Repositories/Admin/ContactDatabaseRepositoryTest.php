<?php

declare(strict_types=1);

namespace Tests\Infra\Repositories\Admin;

use App\Infra\Repositories\Admin\ContactDatabaseRepository;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new ContactDatabaseRepository;
});

test('can count total contact messages', function () {
    ContactMessage::factory()->count(3)->create();

    $count = $this->repository->count();

    expect($count)->toBe(3);
});

test('can count unread contact messages', function () {
    ContactMessage::factory()->create(['read_at' => null]);
    ContactMessage::factory()->create(['read_at' => null]);
    ContactMessage::factory()->create(['read_at' => now()]);

    $unreadCount = $this->repository->unreadCount();

    expect($unreadCount)->toBe(2);
});

test('can find latest contact messages with limit', function () {
    // Create messages with different timestamps
    ContactMessage::factory()->create(['created_at' => now()->subDays(3)]);
    ContactMessage::factory()->create(['created_at' => now()->subDays(2)]);
    ContactMessage::factory()->create(['created_at' => now()->subDays(1)]);
    ContactMessage::factory()->create(['created_at' => now()]);

    $latest = $this->repository->findLatest(2);

    expect($latest)->toHaveCount(2);
    // Should return newest first
    expect($latest->first()->created_at->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
});

test('can create contact message', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Test Subject',
        'message' => 'Test message content',
        'gdpr_consent' => true,
    ];

    $message = $this->repository->create($data);

    expect($message->name)->toBe('John Doe');
    expect($message->email)->toBe('john@example.com');
    expect($message->subject)->toBe('Test Subject');
    expect($message->message)->toBe('Test message content');
    expect($message->gdpr_consent)->toBe(true);

    $this->assertDatabaseHas('contact_messages', $data);
});

test('can find contact message by id', function () {
    $message = ContactMessage::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $found = $this->repository->findById($message->id);

    expect($found)->not->toBeNull();
    expect($found->name)->toBe('Jane Doe');
    expect($found->email)->toBe('jane@example.com');
});

test('returns null when contact message not found', function () {
    $found = $this->repository->findById(999);

    expect($found)->toBeNull();
});

test('can paginate contact messages', function () {
    ContactMessage::factory()->count(25)->create();

    $paginated = $this->repository->paginate(10);

    expect($paginated->total())->toBe(25);
    expect($paginated->perPage())->toBe(10);
    expect($paginated->count())->toBe(10); // First page should have 10 items
    expect($paginated->currentPage())->toBe(1);
});

test('paginated messages are ordered by latest first', function () {
    $oldest = ContactMessage::factory()->create(['created_at' => now()->subDays(2)]);
    $newest = ContactMessage::factory()->create(['created_at' => now()]);
    $middle = ContactMessage::factory()->create(['created_at' => now()->subDays(1)]);

    $paginated = $this->repository->paginate(5);

    expect($paginated->items())->toHaveCount(3);
    expect($paginated->items()[0]->id)->toBe($newest->id);
    expect($paginated->items()[1]->id)->toBe($middle->id);
    expect($paginated->items()[2]->id)->toBe($oldest->id);
});

test('can mark existing message as read', function () {
    $message = ContactMessage::factory()->create(['read_at' => null]);

    expect($message->read_at)->toBeNull();

    $result = $this->repository->markAsRead($message->id);

    expect($result)->toBeTrue();

    $message->refresh();
    expect($message->read_at)->not->toBeNull();
});

test('returns false when trying to mark non-existent message as read', function () {
    $result = $this->repository->markAsRead(999);

    expect($result)->toBeFalse();
});
