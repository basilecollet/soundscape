<?php

declare(strict_types=1);

namespace App\Domain\Admin\Repositories;

use App\Models\PageContent;
use Illuminate\Database\Eloquent\Collection;

interface ContentRepository
{
    public function findById(int $id): PageContent;

    /**
     * Create or update content
     *
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): PageContent;

    /**
     * @return Collection<int, PageContent>
     */
    public function getAll(): Collection;

    public function delete(int $id): void;

    /**
     * @return Collection<int, PageContent>
     */
    public function findByPage(string $page): Collection;

    /**
     * @return Collection<int, PageContent>
     */
    public function search(string $term): Collection;

    /**
     * @return array<string>
     */
    public function getExistingKeysForPage(string $page): array;

    public function count(): int;

    /**
     * @return Collection<int, PageContent>
     */
    public function findLatest(int $limit): Collection;

    /**
     * Check if a key already exists
     */
    public function existsByKey(string $key): bool;

    /**
     * Check if a key exists excluding a specific ID (for updates)
     */
    public function existsByKeyExcludingId(string $key, int $excludeId): bool;

    /**
     * Get filtered and sorted contents for Livewire components
     *
     * @return Collection<int, PageContent>
     */
    public function getFilteredAndSortedContents(string $page, string $search): Collection;

    /**
     * Get missing keys for all pages
     *
     * @return array<string, array<string>>
     */
    public function getMissingKeysForAllPages(): array;
}
