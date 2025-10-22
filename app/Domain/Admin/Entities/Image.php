<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities;

use App\Domain\Admin\Exceptions\InvalidImageException;

final readonly class Image
{
    public function __construct(
        public string $originalUrl,
        public string $thumbUrl,
        public string $webUrl,
        public string $previewUrl,
        public ?string $alt = null,
    ) {
        $this->validateUrls();
    }

    private function validateUrls(): void
    {
        $this->validateUrl($this->originalUrl, 'original');
        $this->validateUrl($this->thumbUrl, 'thumbnail');
        $this->validateUrl($this->webUrl, 'web');
        $this->validateUrl($this->previewUrl, 'preview');
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
