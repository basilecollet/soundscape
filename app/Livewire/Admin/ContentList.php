<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Application\Admin\Services\ContentManagementService;
use App\Domain\Admin\Enums\ContentKeys;
use App\Models\PageContent;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class ContentList extends Component
{
    use WithPagination;

    public string $selectedPage = 'all';

    public string $search = '';

    private function getContentManagementService(): ContentManagementService
    {
        return app(ContentManagementService::class);
    }

    public function mount(): void
    {
        //
    }

    public function updatedSelectedPage(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getContentsProperty(): Collection
    {
        return $this->getContentManagementService()->getFilteredAndSortedContents($this->selectedPage, $this->search);
    }

    public function getAvailablePagesProperty(): array
    {
        return ContentKeys::getAvailablePages();
    }

    public function getMissingKeysProperty(): array
    {
        return $this->getContentManagementService()->getMissingKeysForAllPages();
    }

    public function createMissingContent(string $page, string $key): void
    {
        $this->getContentManagementService()->createContentFromKey($page, $key, ContentKeys::getLabel($key));
        $this->dispatch('content-created', key: $key);
    }

    public function render(): View
    {
        return view('livewire.admin.content-list', [
            'contents' => $this->contents,
            'availablePages' => $this->availablePages,
            'missingKeys' => $this->missingKeys,
        ]);
    }
}
