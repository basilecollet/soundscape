<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Exceptions;

final class InvalidProjectSlugException extends \DomainException
{
    private string $slug;

    public static function invalidFormat(string $slug): self
    {
        $instance = new self(
            sprintf(
                "Technical: Invalid slug format: '%s'. Slug must contain only lowercase letters, numbers, and hyphens.",
                $slug
            )
        );
        $instance->slug = $slug;

        return $instance;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
