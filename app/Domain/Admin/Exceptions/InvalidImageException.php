<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidImageException extends \DomainException
{
    private ?string $urlType = null;

    private ?string $url = null;

    public static function emptyUrl(string $urlType): self
    {
        $instance = new self(
            sprintf('Technical: Image %s URL cannot be empty', $urlType)
        );
        $instance->urlType = $urlType;

        return $instance;
    }

    public static function invalidUrlFormat(string $url): self
    {
        $instance = new self(
            sprintf('Technical: Invalid URL format: %s', $url)
        );
        $instance->url = $url;

        return $instance;
    }

    public function getUrlType(): ?string
    {
        return $this->urlType;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
