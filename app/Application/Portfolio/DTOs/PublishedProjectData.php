<?php

declare(strict_types=1);

namespace App\Application\Portfolio\DTOs;

use App\Domain\Portfolio\Entities\PublishedProject;

final readonly class PublishedProjectData
{
    public function __construct(
        public string $title,
        public string $slug,
        public ?string $shortDescription = null,
        public ?string $projectDate = null,
        public ?ImageData $featuredImage = null,
    ) {}

    public static function fromEntity(PublishedProject $project): self
    {
        $featuredImage = null;
        if ($project->getFeaturedImage() !== null) {
            $featuredImage = ImageData::fromEntity($project->getFeaturedImage());
        }

        return new self(
            title: (string) $project->getTitle(),
            slug: (string) $project->getSlug(),
            shortDescription: $project->getShortDescription() !== null ? (string) $project->getShortDescription() : null,
            projectDate: $project->getProjectDate()?->format('Y-m-d'),
            featuredImage: $featuredImage,
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
            'short_description' => $this->shortDescription,
            'project_date' => $this->projectDate,
            'featured_image' => $this->featuredImage?->toArray(),
        ];
    }
}
