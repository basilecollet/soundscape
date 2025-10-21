<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities;

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
        if (trim($this->webUrl) === '') {
            throw new \InvalidArgumentException('Image URLs cannot be empty');
        }

        if (trim($this->thumbUrl) === '') {
            throw new \InvalidArgumentException('Image URLs cannot be empty');
        }
    }
}
