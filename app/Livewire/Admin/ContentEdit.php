<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Domain\Admin\Enums\ContentKeys;
use App\Models\PageContent;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ContentEdit extends Component
{
    public ?int $contentId = null;
    public string $content = '';
    public string $title = '';
    public string $key = '';
    public string $page = 'home';

    private ?PageContent $originalContent = null;

    public function mount(?int $contentId = null): void
    {
        $this->contentId = $contentId;

        if ($contentId) {
            $this->originalContent = PageContent::findOrFail($contentId);
            $this->content = $this->originalContent->content;
            $this->title = $this->originalContent->title;
            $this->key = $this->originalContent->key;
            $this->page = $this->originalContent->page;
        }
    }

    public function getAvailablePagesProperty(): array
    {
        return ContentKeys::getAvailablePages();
    }

    public function getAvailableKeysProperty(): array
    {
        return ContentKeys::getKeysForPage($this->page);
    }

    public function updatedPage(): void
    {
        // Reset key when page changes for new content
        if (!$this->contentId) {
            $this->key = '';
        }
    }

    public function save(): void
    {
        $rules = [
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
            'key' => 'required|string',
            'page' => 'required|string|in:' . implode(',', ContentKeys::getAvailablePages()),
        ];

        // Add uniqueness validation for new content
        if (!$this->contentId) {
            $rules['key'] .= '|unique:page_contents,key';
        }

        $this->validate($rules);

        // Validate key is valid for the selected page
        if (!ContentKeys::isValidKeyForPage($this->key, $this->page)) {
            $this->addError('key', 'The selected key is not valid for the ' . $this->page . ' page.');
            return;
        }

        if ($this->contentId) {
            // Update existing content
            if (!$this->originalContent) {
                $this->originalContent = PageContent::findOrFail($this->contentId);
            }
            $this->originalContent->update([
                'content' => $this->content,
                'title' => $this->title,
            ]);
        } else {
            // Create new content
            PageContent::create([
                'key' => $this->key,
                'content' => $this->content,
                'title' => $this->title,
                'page' => $this->page,
            ]);
        }

        $this->dispatch('content-saved');
    }

    public function cancel(): void
    {
        if ($this->contentId) {
            if (!$this->originalContent) {
                $this->originalContent = PageContent::findOrFail($this->contentId);
            }
            $this->content = $this->originalContent->content;
            $this->title = $this->originalContent->title;
            $this->key = $this->originalContent->key;
            $this->page = $this->originalContent->page;
        } else {
            $this->reset(['content', 'title', 'key']);
            $this->page = 'home';
        }
    }

    public function delete(): void
    {
        if ($this->contentId) {
            if (!$this->originalContent) {
                $this->originalContent = PageContent::findOrFail($this->contentId);
            }
            $this->originalContent->delete();
            $this->dispatch('content-deleted');
        }
    }

    public function render(): View
    {
        return view('livewire.admin.content-edit', [
            'availablePages' => $this->availablePages,
            'availableKeys' => $this->availableKeys,
        ]);
    }
}
