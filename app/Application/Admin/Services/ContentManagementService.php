<?php

declare(strict_types=1);

namespace App\Application\Admin\Services;

use App\Application\Admin\DTOs\ContentData;
use App\Application\Admin\DTOs\ContentListFilterData;
use App\Application\Admin\DTOs\ContentUpdateData;
use App\Domain\Admin\Repositories\ContentRepository;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Collection;

class ContentManagementService
{
    public function __construct(
        private readonly ContentRepository $contentRepository
    ) {}

    public function updateContent(ContentUpdateData $data): void
    {
        $this->contentRepository->store([
            'key' => $this->getContentById($data->id)->key, // Garder la même clé
            'content' => $data->content,
            'title' => $data->title,
        ]);
    }

    public function getContentById(int $id): PageContent
    {
        return $this->contentRepository->findById($id);
    }

    /**
     * @return Collection<int, PageContent>
     */
    public function getAllContent(): Collection
    {
        return $this->contentRepository->getAll();
    }

    public function createContent(ContentData $data): PageContent
    {
        $storeData = [];

        if ($data->key) {
            $storeData['key'] = $data->key;
        }
        if ($data->content) {
            $storeData['content'] = $data->content;
        }
        if ($data->title) {
            $storeData['title'] = $data->title;
        }
        if ($data->page) {
            $storeData['page'] = $data->page;
        }

        return $this->contentRepository->store($storeData);
    }

    public function updateContentWithData(ContentData $data): void
    {
        $existing = $this->getContentById($data->id);

        $storeData = [
            'key' => $existing->key,
            'page' => $existing->page,
        ];

        if ($data->content) {
            $storeData['content'] = $data->content;
        }
        if ($data->title !== null) {
            $storeData['title'] = $data->title;
        }

        $this->contentRepository->store($storeData);
    }

    public function deleteContent(int $id): void
    {
        $this->contentRepository->delete($id);
    }

    /**
     * @return Collection<int, PageContent>
     */
    public function getContentsByPage(string $page): Collection
    {
        return $this->contentRepository->findByPage($page);
    }

    /**
     * @return Collection<int, PageContent>
     */
    public function searchContents(string $term): Collection
    {
        return $this->contentRepository->search($term);
    }

    /**
     * @return array<string>
     */
    public function getExistingKeysForPage(string $page): array
    {
        return $this->contentRepository->getExistingKeysForPage($page);
    }

    /**
     * @return Collection<int, PageContent>
     */
    public function getFilteredContents(ContentListFilterData $filter): Collection
    {
        if ($filter->hasSearch()) {
            $contents = $this->searchContents($filter->search);

            if (! $filter->isAllPages()) {
                $contents = $contents->filter(fn ($content) => $content->page === $filter->page);
            }

            return $contents;
        }

        if (! $filter->isAllPages()) {
            return $this->getContentsByPage($filter->page);
        }

        return $this->getAllContent();
    }

    /**
     * Find content for editing purposes (allows null return)
     */
    public function findContentForEditing(int $id): ?PageContent
    {
        try {
            return $this->contentRepository->findById($id);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Validate if a key is unique (for new content) or unique excluding current ID (for updates)
     */
    public function validateUniqueKey(string $key, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            return ! $this->contentRepository->existsByKeyExcludingId($key, $excludeId);
        }

        return ! $this->contentRepository->existsByKey($key);
    }

    /**
     * Get filtered and sorted contents for Livewire components
     *
     * @return Collection<int, PageContent>
     */
    public function getFilteredAndSortedContents(string $page, string $search): Collection
    {
        return $this->contentRepository->getFilteredAndSortedContents($page, $search);
    }

    /**
     * Get missing keys for all pages
     *
     * @return array<string, array<string>>
     */
    public function getMissingKeysForAllPages(): array
    {
        return $this->contentRepository->getMissingKeysForAllPages();
    }

    /**
     * Create content from key with optional default title
     */
    public function createContentFromKey(string $page, string $key, ?string $defaultTitle = null): PageContent
    {
        return $this->contentRepository->store([
            'key' => $key,
            'content' => '',
            'title' => $defaultTitle ?? '',
            'page' => $page,
        ]);
    }
}
