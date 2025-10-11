<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class ProjectCannotBeDraftedException extends \DomainException
{
    public static function alreadyDraft(): self
    {
        return new self('Project is already in draft status');
    }
}