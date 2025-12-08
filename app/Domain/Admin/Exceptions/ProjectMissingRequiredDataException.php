<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class ProjectMissingRequiredDataException extends \DomainException
{
    public static function missingDescription(): self
    {
        return new self('Technical: Cannot publish project without description');
    }
}
