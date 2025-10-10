<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;

final class DuplicateProjectSlugException extends \DomainException
{
    public static function forSlug(ProjectSlug $slug): self
    {
        return new self(
            sprintf('A project with slug "%s" already exists.', (string) $slug)
        );
    }
}
