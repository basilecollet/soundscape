<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidImageException extends \DomainException
{
    public static function emptyUrl(string $urlType): self
    {
        return new self(
            sprintf('Image %s URL cannot be empty', $urlType)
        );
    }

    public static function invalidUrlFormat(string $url): self
    {
        return new self(
            sprintf('Invalid URL format: %s', $url)
        );
    }
}
