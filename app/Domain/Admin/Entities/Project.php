<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities;

use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Entities\ValueObjects\ClientName;
use App\Domain\Admin\Entities\ValueObjects\ProjectDate;
use App\Domain\Admin\Entities\ValueObjects\ProjectDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;

final class Project
{
    private function __construct(
        private ProjectTitle $title,
        private readonly ProjectSlug $slug,
        private ProjectStatus $status,
        private ?ProjectDescription $description = null,
        private ?ProjectShortDescription $shortDescription = null,
        private ?ClientName $clientName = null,
        private ?ProjectDate $projectDate = null,
    ) {}

    public static function new(
        string $title,
        ?string $description = null,
        ?string $shortDescription = null,
        ?string $clientName = null,
        ?string $projectDate = null,
    ): self {
        $projectTitle = ProjectTitle::fromString($title);

        // Convert empty strings to null
        $description = $description !== null && trim($description) !== '' ? $description : null;
        $shortDescription = $shortDescription !== null && trim($shortDescription) !== '' ? $shortDescription : null;
        $clientName = $clientName !== null && trim($clientName) !== '' ? $clientName : null;
        $projectDate = $projectDate !== null && trim($projectDate) !== '' ? $projectDate : null;

        return new self(
            $projectTitle,
            ProjectSlug::fromTitle($projectTitle),
            ProjectStatus::Draft,
            $description !== null ? ProjectDescription::fromString($description) : null,
            $shortDescription !== null ? ProjectShortDescription::fromString($shortDescription) : null,
            $clientName !== null ? ClientName::fromString($clientName) : null,
            $projectDate !== null ? ProjectDate::fromString($projectDate) : null,
        );
    }

    public static function reconstitute(
        ProjectTitle $title,
        ProjectSlug $slug,
        ProjectStatus $status,
        ?ProjectDescription $description = null,
        ?ProjectShortDescription $shortDescription = null,
        ?ClientName $clientName = null,
        ?ProjectDate $projectDate = null,
    ): self {
        return new self(
            $title,
            $slug,
            $status,
            $description,
            $shortDescription,
            $clientName,
            $projectDate,
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

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function getDescription(): ?ProjectDescription
    {
        return $this->description;
    }

    public function getShortDescription(): ?ProjectShortDescription
    {
        return $this->shortDescription;
    }

    public function getClientName(): ?ClientName
    {
        return $this->clientName;
    }

    public function getProjectDate(): ?ProjectDate
    {
        return $this->projectDate;
    }

    public function publish(): void
    {
        $this->status = ProjectStatus::Published;
    }

    public function archive(): void
    {
        $this->status = ProjectStatus::Archived;
    }

    public function draft(): void
    {
        $this->status = ProjectStatus::Draft;
    }

    public function update(
        ProjectTitle $title,
        ?ProjectDescription $description = null,
        ?ProjectShortDescription $shortDescription = null,
        ?ClientName $clientName = null,
        ?ProjectDate $projectDate = null,
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->shortDescription = $shortDescription;
        $this->clientName = $clientName;
        $this->projectDate = $projectDate;
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'title' => (string) $this->title,
            'slug' => (string) $this->slug,
            'status' => $this->status->value,
            'description' => $this->description !== null ? (string) $this->description : null,
            'short_description' => $this->shortDescription !== null ? (string) $this->shortDescription : null,
            'client_name' => $this->clientName !== null ? (string) $this->clientName : null,
            'project_date' => $this->projectDate?->format('Y-m-d'),
        ];
    }
}
