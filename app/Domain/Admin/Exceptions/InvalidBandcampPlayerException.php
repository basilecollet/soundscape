<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidBandcampPlayerException extends \DomainException
{
    public static function notIframe(): self
    {
        return new self('Bandcamp player must be an iframe tag');
    }

    public static function notBandcamp(): self
    {
        return new self('Bandcamp player must contain bandcamp.com domain');
    }

    public static function tooLong(int $length, int $maxLength = 10000): self
    {
        return new self(
            sprintf('Bandcamp player code cannot exceed %d characters (got %d)', $maxLength, $length)
        );
    }
}
