<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidClientNameException extends \DomainException
{
    private ?int $length = null;
    private ?int $maxLength = null;
    private ?string $value = null;

    public static function empty(): self
    {
        return new self('Technical: Client name cannot be empty');
    }

    public static function tooLong(int $length, int $maxLength = 255): self
    {
        $instance = new self(
            sprintf('Technical: Client name cannot exceed %d characters (got %d)', $maxLength, $length)
        );
        $instance->length = $length;
        $instance->maxLength = $maxLength;

        return $instance;
    }

    public static function processingError(string $value): self
    {
        $instance = new self(
            sprintf('Technical: Failed to process client name: "%s"', $value)
        );
        $instance->value = $value;

        return $instance;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
