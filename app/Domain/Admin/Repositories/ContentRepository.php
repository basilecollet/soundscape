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
     * @param array<string, mixed> $data
     */
    public function store(array $data): PageContent;

    /**
     * @return Collection<int, PageContent>
     */
    public function getAll(): Collection;
}
