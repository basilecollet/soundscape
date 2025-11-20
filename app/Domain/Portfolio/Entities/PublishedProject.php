<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities;

use App\Domain\Portfolio\Entities\ValueObjects\ProjectDate;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectSlug;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectTitle;

final readonly class PublishedProject
{
    /**
     * @param  array<Image>  $galleryImages
     */
    private function __construct(
        private ProjectTitle $title,
        private ProjectSlug $slug,
        private ProjectDescription $description,
        private ?ProjectShortDescription $shortDescription = null,
        private ?ProjectDate $projectDate = null,
        private ?Image $featuredImage = null,
        private array $galleryImages = [],
    ) {}

    /**
     * @param  array<Image>  $galleryImages
     */
    public static function reconstitute(
        ProjectTitle $title,
        ProjectSlug $slug,
        ProjectDescription $description,
        ?ProjectShortDescription $shortDescription = null,
        ?ProjectDate $projectDate = null,
        ?Image $featuredImage = null,
        array $galleryImages = [],
    ): self {
        return new self(
            title: $title,
            slug: $slug,
            description: $description,
            shortDescription: $shortDescription,
            projectDate: $projectDate,
            featuredImage: $featuredImage,
            galleryImages: $galleryImages,
        );
    }

    public function getTitle(): ProjectTitle
    {
        return $this->title;
    }

    public function getSlug(): ProjectSlug
    {
        return $this->slug;
    }

    public function getDescription(): ProjectDescription
    {
        return $this->description;
    }

    public function getShortDescription(): ?ProjectShortDescription
    {
        return $this->shortDescription;
    }

    public function getProjectDate(): ?ProjectDate
    {
        return $this->projectDate;
    }

    public function getFeaturedImage(): ?Image
    {
        return $this->featuredImage;
    }

    /**
     * @return array<Image>
     */
    public function getGalleryImages(): array
    {
        return $this->galleryImages;
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'title' => (string) $this->title,
            'slug' => (string) $this->slug,
            'description' => (string) $this->description,
            'short_description' => $this->shortDescription !== null ? (string) $this->shortDescription : null,
            'project_date' => $this->projectDate?->format('Y-m-d'),
        ];
    }
}
