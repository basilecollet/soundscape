<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities\ValueObjects;

use App\Domain\Portfolio\Exceptions\InvalidProjectTitleException;

final readonly class ProjectTitle
{
    private function __construct(
        private string $title,
    ) {}

    public static function fromString(string $title): self
    {
        $title = trim($title);

        if (empty($title)) {
            throw InvalidProjectTitleException::empty();
        }

        if (mb_strlen($title) > 255) {
            throw InvalidProjectTitleException::tooLong(mb_strlen($title));
        }

        return new self($title);
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
