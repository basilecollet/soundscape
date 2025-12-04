<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities\ValueObjects;

final readonly class BandcampPlayer
{
    private function __construct(
        private ?string $iframe,
    ) {}

    public static function fromString(?string $iframe): self
    {
        if ($iframe === null || trim($iframe) === '') {
            return new self(null);
        }

        return new self(trim($iframe));
    }

    public function toString(): ?string
    {
        return $this->iframe;
    }

    public function hasPlayer(): bool
    {
        return $this->iframe !== null;
    }

    public function __toString(): string
    {
        return $this->iframe ?? '';
    }
}
