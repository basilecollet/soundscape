<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities;

use App\Domain\Portfolio\Entities\ValueObjects\ProjectDate;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectSlug;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectTitle;

final readonly class PublishedProject
{
    private function __construct(
        private ProjectTitle $title,
        private ProjectSlug $slug,
        private ?ProjectShortDescription $shortDescription = null,
        private ?ProjectDate $projectDate = null,
        private ?Image $featuredImage = null,
    ) {}

    public static function reconstitute(
        ProjectTitle $title,
        ProjectSlug $slug,
        ?ProjectShortDescription $shortDescription = null,
        ?ProjectDate $projectDate = null,
        ?Image $featuredImage = null,
    ): self {
        return new self(
            title: $title,
            slug: $slug,
            shortDescription: $shortDescription,
            projectDate: $projectDate,
            featuredImage: $featuredImage,
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
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'title' => (string) $this->title,
            'slug' => (string) $this->slug,
            'short_description' => $this->shortDescription !== null ? (string) $this->shortDescription : null,
            'project_date' => $this->projectDate?->format('Y-m-d'),
        ];
    }
}
