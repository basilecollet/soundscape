<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

use App\Domain\Admin\Exceptions\InvalidProjectDescriptionException;

final readonly class ProjectDescription
{
    private function __construct(
        private string $description,
    ) {}

    public static function fromString(string $description): self
    {
        $description = trim($description);

        if (empty($description)) {
            throw InvalidProjectDescriptionException::empty();
        }

        return new self($description);
    }

    public function __toString(): string
    {
        return $this->description;
    }
}
