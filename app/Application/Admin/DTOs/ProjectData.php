<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

use App\Domain\Admin\Entities\Project;

final readonly class ProjectData
{
    public function __construct(
        public string $title,
        public string $slug,
        public string $status,
        public ?string $description,
        public ?string $shortDescription,
        public ?string $clientName,
        public ?string $projectDate,
    ) {}

    public static function fromEntity(Project $project): self
    {
        return new self(
            title: (string) $project->getTitle(),
            slug: (string) $project->getSlug(),
            status: $project->getStatus()->value,
            description: $project->getDescription() !== null ? (string) $project->getDescription() : null,
            shortDescription: $project->getShortDescription() !== null ? (string) $project->getShortDescription() : null,
            clientName: $project->getClientName() !== null ? (string) $project->getClientName() : null,
            projectDate: $project->getProjectDate()?->format('Y-m-d'),
        );
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'description' => $this->description,
            'short_description' => $this->shortDescription,
            'client_name' => $this->clientName,
            'project_date' => $this->projectDate,
        ];
    }
}