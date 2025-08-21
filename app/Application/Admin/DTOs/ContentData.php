<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

class ContentData
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly string $content = '',
        public readonly ?string $title = null,
        public readonly ?string $key = null,
        public readonly ?string $page = null
    ) {}

    public static function forCreation(string $key, string $content, string $page, ?string $title = null): self
    {
        return new self(null, $content, $title, $key, $page);
    }

    public static function forUpdate(int $id, string $content, ?string $title = null): self
    {
        return new self($id, $content, $title);
    }

    public function isCreation(): bool
    {
        return $this->id === null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            content: $data['content'] ?? '',
            title: $data['title'] ?? null,
            key: $data['key'] ?? null,
            page: $data['page'] ?? null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'title' => $this->title,
            'key' => $this->key,
            'page' => $this->page,
        ];
    }
}