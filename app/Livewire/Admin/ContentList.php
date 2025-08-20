<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

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
        $query = PageContent::query()
            ->orderBy('page')
            ->orderBy('key');

        if ($this->selectedPage !== 'all') {
            $query->where('page', $this->selectedPage);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('key', 'like', '%'.$this->search.'%')
                    ->orWhere('title', 'like', '%'.$this->search.'%')
                    ->orWhere('content', 'like', '%'.$this->search.'%');
            });
        }

        return $query->get();
    }

    public function getAvailablePagesProperty(): array
    {
        return ContentKeys::getAvailablePages();
    }

    public function getMissingKeysProperty(): array
    {
        $missingKeys = [];

        foreach (ContentKeys::getAvailablePages() as $page) {
            $existingKeys = PageContent::where('page', $page)->pluck('key')->toArray();
            $requiredKeys = ContentKeys::getKeysForPage($page);
            $missing = array_diff($requiredKeys, $existingKeys);

            if (! empty($missing)) {
                $missingKeys[$page] = $missing;
            }
        }

        return $missingKeys;
    }

    public function createMissingContent(string $page, string $key): void
    {
        PageContent::create([
            'key' => $key,
            'content' => '',
            'title' => ContentKeys::getLabel($key),
            'page' => $page,
        ]);

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
