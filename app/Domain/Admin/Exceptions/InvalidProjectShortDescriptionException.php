<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidProjectShortDescriptionException extends \DomainException
{
    public static function empty(): self
    {
        return new self('Project short description cannot be empty');
    }

    public static function tooLong(int $length, int $maxLength = 500): self
    {
        return new self(
            sprintf('Project short description cannot exceed %d characters (got %d)', $maxLength, $length)
        );
    }
}
