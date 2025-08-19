<?php

declare(strict_types=1);

namespace App\Application\Admin\Services;

use App\Application\Admin\DTOs\ContentUpdateData;
use App\Domain\Admin\Repositories\ContentRepository;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Collection;

class ContentManagementService
{
    public function __construct(
        private readonly ContentRepository $contentRepository
    ) {}

    public function updateContent(ContentUpdateData $data): bool
    {
        $stored = $this->contentRepository->store([
            'key' => $this->getContentById($data->id)->key, // Garder la même clé
            'content' => $data->content,
            'title' => $data->title,
        ]);

        return $stored !== null;
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
}
