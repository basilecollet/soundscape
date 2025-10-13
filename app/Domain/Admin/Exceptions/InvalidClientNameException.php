<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidClientNameException extends \DomainException
{
    public static function empty(): self
    {
        return new self('Client name cannot be empty');
    }

    public static function tooLong(int $length, int $maxLength = 255): self
    {
        return new self(
            sprintf('Client name cannot exceed %d characters (got %d)', $maxLength, $length)
        );
    }

    public static function processingError(string $value): self
    {
        return new self(
            sprintf('Failed to process client name: "%s"', $value)
        );
    }
}
