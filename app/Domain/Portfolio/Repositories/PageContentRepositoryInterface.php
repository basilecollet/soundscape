<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Repositories;

use App\Domain\Portfolio\ValueObjects\PageField;

interface PageContentRepositoryInterface
{
    /**
     * Get all fields for a specific page
     *
     * @param  string  $page  (home, about, contact)
     * @return array<PageField>
     */
    public function getFieldsForPage(string $page): array;
}
