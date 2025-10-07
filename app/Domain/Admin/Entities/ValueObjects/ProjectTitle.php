<?php

namespace App\Domain\Admin\Entities\ValueObjects;

final readonly class ProjectTitle
{
    private function __construct(
        private string $title,
    ) {}

    public static function fromString(string $title): self
    {
        return new self($title);
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
