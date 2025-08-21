<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\DashboardStatistics;
use App\Application\Admin\Services\DashboardService;
use App\Domain\Admin\Repositories\ContactRepository;
use App\Domain\Admin\Repositories\ContentRepository;
use App\Models\ContactMessage;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->contentRepository = Mockery::mock(ContentRepository::class);
    $this->contactRepository = Mockery::mock(ContactRepository::class);
    $this->service = new DashboardService($this->contentRepository, $this->contactRepository);
});

describe('getStatistics method', function () {
    test('can get dashboard statistics using repositories', function () {
        $this->contentRepository->shouldReceive('count')
            ->andReturn(15);

        $this->contactRepository->shouldReceive('unreadCount')
            ->andReturn(3);

        $lastUpdate = now();
        $lastContent = (object) ['updated_at' => $lastUpdate];
        $latestContents = new Collection([$lastContent]);

        $this->contentRepository->shouldReceive('findLatest')
            ->with(1)
            ->andReturn($latestContents);

        $statistics = $this->service->getStatistics();

        expect($statistics)->toBeInstanceOf(DashboardStatistics::class);
        expect($statistics->totalContent)->toBe(15);
        expect($statistics->recentMessages)->toBe(3);
        expect($statistics->lastContentUpdate)->toBe($lastUpdate);
    });

    test('handles null last content update gracefully', function () {
        $this->contentRepository->shouldReceive('count')
            ->andReturn(0);

        $this->contactRepository->shouldReceive('unreadCount')
            ->andReturn(0);

        $this->contentRepository->shouldReceive('findLatest')
            ->with(1)
            ->andReturn(new Collection([])); // Empty collection

        $statistics = $this->service->getStatistics();

        expect($statistics->totalContent)->toBe(0);
        expect($statistics->recentMessages)->toBe(0);
        expect($statistics->lastContentUpdate)->toBeNull();
    });
});

describe('getRecentContactMessages method', function () {
    test('can get recent contact messages using repository', function () {
        $messages = new Collection([
            new ContactMessage(['name' => 'John Doe', 'email' => 'john@example.com']),
            new ContactMessage(['name' => 'Jane Smith', 'email' => 'jane@example.com']),
        ]);

        $this->contactRepository->shouldReceive('findLatest')
            ->with(5)
            ->andReturn($messages);

        $result = $this->service->getRecentContactMessages(5);

        expect($result)->toBe($messages);
        expect($result)->toHaveCount(2);
    });

    test('can get recent contact messages with custom limit', function () {
        $messages = new Collection([
            new ContactMessage(['name' => 'John Doe']),
        ]);

        $this->contactRepository->shouldReceive('findLatest')
            ->with(10)
            ->andReturn($messages);

        $result = $this->service->getRecentContactMessages(10);

        expect($result)->toBe($messages);
        expect($result)->toHaveCount(1);
    });

    test('uses default limit when no limit provided', function () {
        $messages = new Collection([]);

        $this->contactRepository->shouldReceive('findLatest')
            ->with(5) // Default limit
            ->andReturn($messages);

        $result = $this->service->getRecentContactMessages();

        expect($result)->toBe($messages);
    });
});
