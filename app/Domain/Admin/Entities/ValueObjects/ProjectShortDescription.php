<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

final readonly class ProjectShortDescription
{
    private function __construct(
        private string $shortDescription,
    ) {}

    public static function fromString(string $shortDescription): self
    {
        return new self(trim($shortDescription));
    }

    public function __toString(): string
    {
        return $this->shortDescription;
    }
}
