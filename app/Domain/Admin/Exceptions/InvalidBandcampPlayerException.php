<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidBandcampPlayerException extends \DomainException
{
    private ?int $length = null;
    private ?int $maxLength = null;

    public static function notIframe(): self
    {
        return new self('Technical: Bandcamp player must be an iframe tag');
    }

    public static function notBandcamp(): self
    {
        return new self('Technical: Bandcamp player must contain bandcamp.com domain');
    }

    public static function tooLong(int $length, int $maxLength = 10000): self
    {
        $instance = new self(
            sprintf('Technical: Bandcamp player code cannot exceed %d characters (got %d)', $maxLength, $length)
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
