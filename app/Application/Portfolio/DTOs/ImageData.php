<?php

declare(strict_types=1);

namespace App\Application\Portfolio\DTOs;

use App\Domain\Portfolio\Entities\Image;

final readonly class ImageData
{
    public function __construct(
        public string $webUrl,
        public string $thumbUrl,
        public ?string $alt = null,
    ) {}

    public static function fromEntity(Image $image): self
    {
        return new self(
            webUrl: $image->webUrl,
            thumbUrl: $image->thumbUrl,
            alt: $image->alt,
        );
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'web_url' => $this->webUrl,
            'thumb_url' => $this->thumbUrl,
            'alt' => $this->alt,
        ];
    }
}
