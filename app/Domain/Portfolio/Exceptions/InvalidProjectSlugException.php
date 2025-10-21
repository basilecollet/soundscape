<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Exceptions;

final class InvalidProjectSlugException extends \DomainException
{
    public static function invalidFormat(string $slug): self
    {
        return new self(
            sprintf(
                "Invalid slug format: '%s'. Slug must contain only lowercase letters, numbers, and hyphens.",
                $slug
            )
        );
    }
}
