<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Exceptions;

final class InvalidProjectDateException extends \DomainException
{
    public static function invalidFormat(string $date): self
    {
        return new self(
            sprintf('Invalid date format: "%s". Expected a valid date string.', $date)
        );
    }
}
