<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;

final class ProjectNotFoundException extends \DomainException
{
    public static function forSlug(ProjectSlug $slug): self
    {
        return new self(
            sprintf('Project with slug "%s" was not found.', (string) $slug)
        );
    }
}