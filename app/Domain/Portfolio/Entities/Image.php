<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities;

use App\Domain\Portfolio\Exceptions\InvalidImageException;

final readonly class Image
{
    public function __construct(
        public string $webUrl,
        public string $thumbUrl,
        public ?string $alt = null,
    ) {
        $this->validateUrls();
    }

    private function validateUrls(): void
    {
        $this->validateUrl($this->webUrl, 'web');
        $this->validateUrl($this->thumbUrl, 'thumbnail');
    }

    private function validateUrl(string $url, string $urlType): void
    {
        if (trim($url) === '') {
            throw InvalidImageException::emptyUrl($urlType);
        }

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw InvalidImageException::invalidUrlFormat($url);
        }
    }
}
