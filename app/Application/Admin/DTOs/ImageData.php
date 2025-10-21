<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

use App\Domain\Admin\Entities\Image;

final readonly class ImageData
{
    public function __construct(
        public string $originalUrl,
        public string $thumbUrl,
        public string $webUrl,
        public string $previewUrl,
        public ?string $alt = null,
    ) {}

    public static function fromEntity(Image $image): self
    {
        return new self(
            originalUrl: $image->originalUrl,
            thumbUrl: $image->thumbUrl,
            webUrl: $image->webUrl,
            previewUrl: $image->previewUrl,
            alt: $image->alt,
        );
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'original_url' => $this->originalUrl,
            'thumb_url' => $this->thumbUrl,
            'web_url' => $this->webUrl,
            'preview_url' => $this->previewUrl,
            'alt' => $this->alt,
        ];
    }
}
