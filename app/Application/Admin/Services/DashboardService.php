<?php

declare(strict_types=1);

namespace App\Application\Admin\Services;

use App\Application\Admin\DTOs\DashboardStatistics;
use App\Domain\Admin\Repositories\ContentRepository;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function __construct(
        private readonly ContentRepository $contentRepository,
        private readonly ContactManagementService $contactManagementService
    ) {}

    public function getStatistics(): DashboardStatistics
    {
        $totalContent = $this->contentRepository->count();
        $recentMessages = $this->contactManagementService->getUnreadCount();

        $latestContents = $this->contentRepository->findLatest(1);
        $lastContentUpdate = $latestContents->isNotEmpty()
            ? $latestContents->first()->updated_at
            : null;

        return new DashboardStatistics(
            totalContent: $totalContent,
            recentMessages: $recentMessages,
            lastContentUpdate: $lastContentUpdate
        );
    }

    /**
     * @return Collection<int, \App\Models\ContactMessage>
     */
    public function getRecentContactMessages(int $limit = 5): Collection
    {
        return $this->contactManagementService->getRecentMessages($limit);
    }
}
