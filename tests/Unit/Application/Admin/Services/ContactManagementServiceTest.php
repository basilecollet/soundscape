<?php

declare(strict_types=1);

use App\Application\Admin\Services\ContactManagementService;
use App\Domain\Admin\Repositories\ContactRepository;
use App\Models\ContactMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->repository = Mockery::mock(ContactRepository::class);
    $this->service = new ContactManagementService($this->repository);
});

describe('getUnreadCount method', function () {
    test('can get unread messages count using repository', function () {
        $this->repository->shouldReceive('unreadCount')
            ->andReturn(7);

        $count = $this->service->getUnreadCount();

        expect($count)->toBe(7);
    });

    test('returns zero when no unread messages', function () {
        $this->repository->shouldReceive('unreadCount')
            ->andReturn(0);

        $count = $this->service->getUnreadCount();

        expect($count)->toBe(0);
    });
});

describe('getAllMessages method', function () {
    test('can get all messages with default pagination using repository', function () {
        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $this->repository->shouldReceive('paginate')
            ->with(15) // Default per page
            ->andReturn($paginator);

        $result = $this->service->getAllMessages();

        expect($result)->toBe($paginator);
    });

    test('can get all messages with custom pagination using repository', function () {
        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $this->repository->shouldReceive('paginate')
            ->with(25)
            ->andReturn($paginator);

        $result = $this->service->getAllMessages(25);

        expect($result)->toBe($paginator);
    });
});

describe('markAsRead method', function () {
    test('can mark message as read when message exists', function () {
        $this->repository->shouldReceive('markAsRead')
            ->with(1)
            ->andReturn(true);

        $result = $this->service->markAsRead(1);

        expect($result)->toBeTrue();
    });

    test('returns false when message does not exist', function () {
        $this->repository->shouldReceive('markAsRead')
            ->with(999)
            ->andReturn(false);

        $result = $this->service->markAsRead(999);

        expect($result)->toBeFalse();
    });
});

describe('getRecentMessages method', function () {
    test('can get recent messages with default limit', function () {
        $messages = new Collection([
            new ContactMessage(['name' => 'John Doe']),
            new ContactMessage(['name' => 'Jane Smith']),
        ]);

        $this->repository->shouldReceive('findLatest')
            ->with(5) // Default limit
            ->andReturn($messages);

        $result = $this->service->getRecentMessages();

        expect($result)->toBe($messages);
        expect($result)->toHaveCount(2);
    });

    test('can get recent messages with custom limit', function () {
        $messages = new Collection([
            new ContactMessage(['name' => 'User 1']),
        ]);

        $this->repository->shouldReceive('findLatest')
            ->with(10)
            ->andReturn($messages);

        $result = $this->service->getRecentMessages(10);

        expect($result)->toBe($messages);
        expect($result)->toHaveCount(1);
    });
});