<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Application\Admin\DTOs\ContentData;
use App\Application\Admin\Services\ContentManagementService;
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

    private function getContentManagementService(): ContentManagementService
    {
        return app(ContentManagementService::class);
    }

    public function mount(?int $contentId = null): void
    {
        $this->contentId = $contentId;

        if ($contentId) {
            $this->originalContent = $this->getContentManagementService()->findContentForEditing($contentId);

            if ($this->originalContent) {
                $this->content = $this->originalContent->content;
                $this->title = $this->originalContent->title;
                $this->key = $this->originalContent->key;
                $this->page = $this->originalContent->page;
            }
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
        if (! $this->contentId) {
            $this->key = '';
        }
    }

    public function save(): void
    {
        $rules = [
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
            'key' => 'required|string',
            'page' => 'required|string|in:'.implode(',', ContentKeys::getAvailablePages()),
        ];

        // Validate uniqueness using service
        if (! $this->getContentManagementService()->validateUniqueKey($this->key, $this->contentId)) {
            $this->addError('key', 'The key has already been taken.');

            return;
        }

        $this->validate($rules);

        // Validate key is valid for the selected page
        if (! ContentKeys::isValidKeyForPage($this->key, $this->page)) {
            $this->addError('key', 'The selected key is not valid for the '.$this->page.' page.');

            return;
        }

        if ($this->contentId) {
            // Update existing content using service
            $this->getContentManagementService()->updateContentWithData(
                ContentData::forUpdate(
                    id: $this->contentId,
                    content: $this->content,
                    title: $this->title
                )
            );
        } else {
            // Create new content using service
            $this->getContentManagementService()->createContent(
                ContentData::forCreation(
                    key: $this->key,
                    content: $this->content,
                    page: $this->page,
                    title: $this->title
                )
            );
        }

        $this->dispatch('content-saved');
    }

    public function cancel(): void
    {
        if ($this->contentId) {
            if (! $this->originalContent) {
                $this->originalContent = $this->getContentManagementService()->findContentForEditing($this->contentId);
            }

            if ($this->originalContent) {
                $this->content = $this->originalContent->content;
                $this->title = $this->originalContent->title;
                $this->key = $this->originalContent->key;
                $this->page = $this->originalContent->page;
            }
        } else {
            $this->reset(['content', 'title', 'key']);
            $this->page = 'home';
        }
    }

    public function delete(): void
    {
        if ($this->contentId) {
            $this->getContentManagementService()->deleteContent($this->contentId);
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
