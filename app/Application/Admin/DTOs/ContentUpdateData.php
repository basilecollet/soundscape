<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

class ContentUpdateData
{
    public function __construct(
        public readonly int $id,
        public readonly string $content,
        public readonly ?string $title
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['id'],
            content: $data['content'],
            title: $data['title'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'title' => $this->title,
        ];
    }
}
