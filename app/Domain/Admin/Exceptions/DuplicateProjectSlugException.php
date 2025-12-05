<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;

final class DuplicateProjectSlugException extends \DomainException
{
    private ProjectSlug $slug;

    public static function forSlug(ProjectSlug $slug): self
    {
        $instance = new self(
            sprintf('Technical: A project with slug "%s" already exists.', (string) $slug)
        );
        $instance->slug = $slug;

        return $instance;
    }

    public function getSlug(): ProjectSlug
    {
        return $this->slug;
    }
}
