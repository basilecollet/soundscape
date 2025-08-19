<?php

declare(strict_types=1);

namespace App\Application\Admin\Services;

use App\Application\Admin\DTOs\DashboardStatistics;
use App\Models\ContactMessage;
use App\Models\PageContent;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getStatistics(): DashboardStatistics
    {
        $totalContent = PageContent::count();
        $recentMessages = ContactMessage::whereNull('read_at')->count();
        $lastContentUpdate = PageContent::latest('updated_at')->first()?->updated_at;

        return new DashboardStatistics(
            totalContent: $totalContent,
            recentMessages: $recentMessages,
            lastContentUpdate: $lastContentUpdate
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, ContactMessage>
     */
    public function getRecentContactMessages(int $limit = 5): Collection
    {
        return ContactMessage::latest()
            ->take($limit)
            ->get();
    }
}
