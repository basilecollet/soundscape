<?php

declare(strict_types=1);

namespace App\Application\Admin\Services;

use App\Application\Admin\DTOs\ContentUpdateData;
use App\Models\PageContent;

class ContentManagementService
{
    public function updateContent(ContentUpdateData $data): bool
    {
        $pageContent = PageContent::findOrFail($data->id);

        return $pageContent->update([
            'content' => $data->content,
            'title' => $data->title,
        ]);
    }

    public function getContentById(int $id): PageContent
    {
        return PageContent::findOrFail($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, PageContent>
     */
    public function getAllContent()
    {
        return PageContent::orderBy('page')
            ->orderBy('key')
            ->get();
    }
}
