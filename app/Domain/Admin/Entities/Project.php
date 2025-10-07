<?php

namespace App\Domain\Admin\Entities;

final readonly class Project
{
    private function __construct(
        private string $title,
    ) {
    }

    public static function new(
        string $title,
    ): self {
        return new self($title);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
        ];
    }
}
