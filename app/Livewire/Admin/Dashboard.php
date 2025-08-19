<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Application\Admin\Services\DashboardService;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalContent = 0;

    public int $recentMessagesCount = 0;

    public ?string $lastContentUpdate = null;

    /**
     * @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactMessage>
     */
    public Collection $recentMessages;

    public function mount(DashboardService $dashboardService): void
    {
        $statistics = $dashboardService->getStatistics();
        $this->totalContent = $statistics->totalContent;
        $this->recentMessagesCount = $statistics->recentMessages;
        $this->lastContentUpdate = $statistics->lastContentUpdate?->diffForHumans();

        $this->recentMessages = $dashboardService->getRecentContactMessages();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.dashboard');
    }
}
