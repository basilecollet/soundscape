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
}
