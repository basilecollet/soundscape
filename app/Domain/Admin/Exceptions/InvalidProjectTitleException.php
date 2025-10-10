<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidProjectTitleException extends \DomainException
{
    public static function empty(): self
    {
        return new self('Project title cannot be empty');
    }

    public static function tooLong(int $length, int $maxLength = 255): self
    {
        return new self(
            sprintf('Project title cannot exceed %d characters (got %d)', $maxLength, $length)
        );
    }
}
