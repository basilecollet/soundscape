<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Exceptions;

final class InvalidProjectTitleException extends \DomainException
{
    private ?int $length = null;
    private ?int $maxLength = null;

    public static function empty(): self
    {
        return new self('Technical: Project title cannot be empty');
    }

    public static function tooLong(int $length, int $maxLength = 255): self
    {
        $instance = new self(
            sprintf('Technical: Project title cannot exceed %d characters (got %d)', $maxLength, $length)
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
