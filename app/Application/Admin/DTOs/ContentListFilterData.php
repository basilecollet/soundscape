<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

class ContentListFilterData
{
    public function __construct(
        public readonly string $page = 'all',
        public readonly string $search = '',
        public readonly int $perPage = 15
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            page: $data['page'] ?? 'all',
            search: $data['search'] ?? '',
            perPage: isset($data['perPage']) ? (int) $data['perPage'] : 15
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'search' => $this->search,
            'perPage' => $this->perPage,
        ];
    }

    public function hasSearch(): bool
    {
        return !empty($this->search);
    }

    public function isAllPages(): bool
    {
        return $this->page === 'all';
    }

    public function isForPage(string $page): bool
    {
        return $this->page === $page;
    }
}