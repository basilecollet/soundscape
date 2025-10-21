<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities;

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
        if (trim($this->originalUrl) === '') {
            throw new \InvalidArgumentException('Image URLs cannot be empty');
        }

        if (trim($this->thumbUrl) === '') {
            throw new \InvalidArgumentException('Image URLs cannot be empty');
        }

        if (trim($this->webUrl) === '') {
            throw new \InvalidArgumentException('Image URLs cannot be empty');
        }

        if (trim($this->previewUrl) === '') {
            throw new \InvalidArgumentException('Image URLs cannot be empty');
        }
    }
}
