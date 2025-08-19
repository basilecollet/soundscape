<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

use Carbon\Carbon;

class DashboardStatistics
{
    public function __construct(
        public readonly int $totalContent,
        public readonly int $recentMessages,
        public readonly ?Carbon $lastContentUpdate
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            totalContent: $data['totalContent'] ?? 0,
            recentMessages: $data['recentMessages'] ?? 0,
            lastContentUpdate: isset($data['lastContentUpdate'])
                ? Carbon::parse($data['lastContentUpdate'])
                : null
        );
    }

    public function toArray(): array
    {
        return [
            'totalContent' => $this->totalContent,
            'recentMessages' => $this->recentMessages,
            'lastContentUpdate' => $this->lastContentUpdate?->toDateTimeString(),
        ];
    }
}
