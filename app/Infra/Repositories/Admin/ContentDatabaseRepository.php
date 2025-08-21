<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Admin;

use App\Domain\Admin\Repositories\ContentRepository;
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
}
