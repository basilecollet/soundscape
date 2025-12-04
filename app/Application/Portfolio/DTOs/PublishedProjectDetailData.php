<?php

declare(strict_types=1);

namespace App\Application\Portfolio\DTOs;

use App\Domain\Portfolio\Entities\PublishedProject;

final readonly class PublishedProjectDetailData
{
    /**
     * @param  array<ImageData>  $galleryImages
     */
    public function __construct(
        public string $title,
        public string $slug,
        public string $description,
        public ?string $shortDescription = null,
        public ?string $projectDate = null,
        public ?string $bandcampPlayer = null,
        public ?ImageData $featuredImage = null,
        public array $galleryImages = [],
    ) {}

    public static function fromEntity(PublishedProject $project): self
    {
        $featuredImage = null;
        if ($project->getFeaturedImage() !== null) {
            $featuredImage = ImageData::fromEntity($project->getFeaturedImage());
        }

        $galleryImages = array_map(
            fn ($image) => ImageData::fromEntity($image),
            $project->getGalleryImages()
        );

        return new self(
            title: (string) $project->getTitle(),
            slug: (string) $project->getSlug(),
            description: (string) $project->getDescription(),
            shortDescription: $project->getShortDescription() !== null ? (string) $project->getShortDescription() : null,
            projectDate: $project->getProjectDate()?->format('Y-m-d'),
            bandcampPlayer: $project->getBandcampPlayer()?->toString(),
            featuredImage: $featuredImage,
            galleryImages: $galleryImages,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->shortDescription,
            'project_date' => $this->projectDate,
            'bandcamp_player' => $this->bandcampPlayer,
            'featured_image' => $this->featuredImage?->toArray(),
            'gallery_images' => array_map(fn ($image) => $image->toArray(), $this->galleryImages),
        ];
    }
}
