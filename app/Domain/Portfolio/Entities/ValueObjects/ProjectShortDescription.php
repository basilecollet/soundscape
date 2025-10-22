<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities\ValueObjects;

use App\Domain\Portfolio\Exceptions\InvalidProjectShortDescriptionException;

final readonly class ProjectShortDescription
{
    private function __construct(
        private string $shortDescription,
    ) {}

    public static function fromString(string $shortDescription): self
    {
        $shortDescription = trim($shortDescription);

        if (empty($shortDescription)) {
            throw InvalidProjectShortDescriptionException::empty();
        }

        if (mb_strlen($shortDescription) > 500) {
            throw InvalidProjectShortDescriptionException::tooLong(mb_strlen($shortDescription));
        }

        return new self($shortDescription);
    }

    public function __toString(): string
    {
        return $this->shortDescription;
    }
}
