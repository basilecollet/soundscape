<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\DashboardStatistics;
use Carbon\Carbon;

describe('DashboardStatistics DTO', function () {
    test('can be created with all values', function () {
        $lastUpdate = Carbon::now();
        $stats = new DashboardStatistics(
            totalContent: 42,
            recentMessages: 5,
            lastContentUpdate: $lastUpdate
        );

        expect($stats->totalContent)->toBe(42);
        expect($stats->recentMessages)->toBe(5);
        expect($stats->lastContentUpdate)->toBe($lastUpdate);
    });

    test('can be created with null lastContentUpdate', function () {
        $stats = new DashboardStatistics(
            totalContent: 10,
            recentMessages: 2,
            lastContentUpdate: null
        );

        expect($stats->totalContent)->toBe(10);
        expect($stats->recentMessages)->toBe(2);
        expect($stats->lastContentUpdate)->toBeNull();
    });

    test('can be created from array with all values', function () {
        $data = [
            'totalContent' => 25,
            'recentMessages' => 3,
            'lastContentUpdate' => '2024-01-15 10:30:00',
        ];

        $stats = DashboardStatistics::fromArray($data);

        expect($stats->totalContent)->toBe(25);
        expect($stats->recentMessages)->toBe(3);
        expect($stats->lastContentUpdate)->toBeInstanceOf(Carbon::class);
        expect($stats->lastContentUpdate?->format('Y-m-d H:i:s'))->toBe('2024-01-15 10:30:00');
    });

    test('can be created from array with missing values using defaults', function () {
        $stats = DashboardStatistics::fromArray([]);

        expect($stats->totalContent)->toBe(0);
        expect($stats->recentMessages)->toBe(0);
        expect($stats->lastContentUpdate)->toBeNull();
    });

    test('can be created from array with partial values', function () {
        $data = [
            'totalContent' => 15,
        ];

        $stats = DashboardStatistics::fromArray($data);

        expect($stats->totalContent)->toBe(15);
        expect($stats->recentMessages)->toBe(0);
        expect($stats->lastContentUpdate)->toBeNull();
    });

    test('can be converted to array with all values', function () {
        $lastUpdate = Carbon::parse('2024-01-15 10:30:00');
        $stats = new DashboardStatistics(
            totalContent: 42,
            recentMessages: 5,
            lastContentUpdate: $lastUpdate
        );

        $array = $stats->toArray();

        expect($array)->toBe([
            'totalContent' => 42,
            'recentMessages' => 5,
            'lastContentUpdate' => '2024-01-15 10:30:00',
        ]);
    });

    test('can be converted to array with null lastContentUpdate', function () {
        $stats = new DashboardStatistics(
            totalContent: 10,
            recentMessages: 2,
            lastContentUpdate: null
        );

        $array = $stats->toArray();

        expect($array)->toBe([
            'totalContent' => 10,
            'recentMessages' => 2,
            'lastContentUpdate' => null,
        ]);
    });

    test('roundtrip conversion preserves data', function () {
        $original = [
            'totalContent' => 100,
            'recentMessages' => 7,
            'lastContentUpdate' => '2024-12-10 15:45:30',
        ];

        $stats = DashboardStatistics::fromArray($original);
        $converted = $stats->toArray();

        expect($converted['totalContent'])->toBe($original['totalContent']);
        expect($converted['recentMessages'])->toBe($original['recentMessages']);
        expect($converted['lastContentUpdate'])->toBe($original['lastContentUpdate']);
    });
});
