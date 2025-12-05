<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Exceptions;

final class InvalidProjectDateException extends \DomainException
{
    private string $date;

    public static function invalidFormat(string $date): self
    {
        $instance = new self(
            sprintf('Technical: Invalid date format: "%s". Expected a valid date string.', $date)
        );
        $instance->date = $date;

        return $instance;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
