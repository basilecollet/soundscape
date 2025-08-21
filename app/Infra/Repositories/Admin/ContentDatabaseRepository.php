<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Admin;

use App\Domain\Admin\Repositories\ContentRepository;
use App\Domain\Admin\Enums\ContentKeys;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Collection;

class ContentDatabaseRepository implements ContentRepository
{
    public function findById(int $id): PageContent
    {
        return PageContent::findOrFail($id);
    }

    public function store(array $data): PageContent
    {
        return PageContent::updateOrCreate(
            ['key' => $data['key']],
            $data
        );
    }

    public function getAll(): Collection
    {
        return PageContent::orderBy('page')
            ->orderBy('key')
            ->get();
    }

    public function delete(int $id): void
    {
        PageContent::findOrFail($id)->delete();
    }

    public function findByPage(string $page): Collection
    {
        return PageContent::where('page', $page)
            ->orderBy('key')
            ->get();
    }

    public function search(string $term): Collection
    {
        return PageContent::where(function ($query) use ($term) {
            $query->where('key', 'like', "%{$term}%")
                ->orWhere('title', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%");
        })->orderBy('page')
            ->orderBy('key')
            ->get();
    }

    public function getExistingKeysForPage(string $page): array
    {
        return PageContent::where('page', $page)
            ->pluck('key')
            ->toArray();
    }

    public function count(): int
    {
        return PageContent::count();
    }

    public function findLatest(int $limit): Collection
    {
        return PageContent::latest('updated_at')->take($limit)->get();
    }

    public function existsByKey(string $key): bool
    {
        return PageContent::where('key', $key)->exists();
    }

    public function existsByKeyExcludingId(string $key, int $excludeId): bool
    {
        return PageContent::where('key', $key)
            ->where('id', '!=', $excludeId)
            ->exists();
    }

    public function getFilteredAndSortedContents(string $page, string $search): Collection
    {
        $query = PageContent::query()
            ->orderBy('page')
            ->orderBy('key');

        // Filter by page if not 'all'
        if ($page !== 'all') {
            $query->where('page', $page);
        }

        // Filter by search term if provided
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    public function getMissingKeysForAllPages(): array
    {
        $missingKeys = [];

        foreach (ContentKeys::getAvailablePages() as $page) {
            $existingKeys = PageContent::where('page', $page)
                ->pluck('key')
                ->toArray();
            
            $requiredKeys = ContentKeys::getKeysForPage($page);
            $missing = array_diff($requiredKeys, $existingKeys);

            if (!empty($missing)) {
                $missingKeys[$page] = $missing;
            }
        }

        return $missingKeys;
    }
}
