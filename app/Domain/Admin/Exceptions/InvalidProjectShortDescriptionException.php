<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidProjectShortDescriptionException extends \DomainException
{
    private ?int $length = null;
    private ?int $maxLength = null;

    public static function empty(): self
    {
        return new self('Technical: Project short description cannot be empty');
    }

    public static function tooLong(int $length, int $maxLength = 500): self
    {
        $instance = new self(
            sprintf('Technical: Project short description cannot exceed %d characters (got %d)', $maxLength, $length)
        );
        $instance->length = $length;
        $instance->maxLength = $maxLength;

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
}
