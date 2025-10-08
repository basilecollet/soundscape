<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

final readonly class ProjectDescription
{
    private function __construct(
        private string $description,
    ) {}

    public static function fromString(string $description): self
    {
        return new self(trim($description));
    }

    public function __toString(): string
    {
        return $this->description;
    }
}
