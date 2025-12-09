<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Portfolio;

use App\Domain\Portfolio\Repositories\PageContentRepositoryInterface;
use App\Domain\Portfolio\ValueObjects\PageField;
use App\Models\PageContent;

final readonly class PageContentEloquentRepository implements PageContentRepositoryInterface
{
    /**
     * @return array<PageField>
     */
    public function getFieldsForPage(string $page): array
    {
        $pageContents = PageContent::where('page', $page)->get();

        return $pageContents->map(function (PageContent $content) {
            return PageField::fromKeyAndContent(
                $content->key,
                $content->content
            );
        })->all();
    }
}
