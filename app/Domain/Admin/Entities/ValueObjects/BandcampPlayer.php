<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

use App\Domain\Admin\Exceptions\InvalidBandcampPlayerException;

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

        $iframe = trim($iframe);

        // Validate it's an iframe tag
        if (! str_contains($iframe, '<iframe')) {
            throw InvalidBandcampPlayerException::notIframe();
        }

        // Validate it's from Bandcamp
        if (! str_contains($iframe, 'bandcamp.com')) {
            throw InvalidBandcampPlayerException::notBandcamp();
        }

        // Reasonable length limit for iframe code
        if (mb_strlen($iframe) > 10000) {
            throw InvalidBandcampPlayerException::tooLong(mb_strlen($iframe));
        }

        return new self($iframe);
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
