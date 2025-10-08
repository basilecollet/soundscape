<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities;

use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Entities\ValueObjects\ProjectDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;

final class Project
{
    private function __construct(
        private ProjectTitle $title,
        private ProjectSlug $slug,
        private ProjectStatus $status,
        private ?ProjectDescription $description = null,
    ) {}

    public static function new(
        string $title,
        ?string $description = null,
    ): self {
        $projectTitle = ProjectTitle::fromString($title);

        return new self(
            $projectTitle,
            ProjectSlug::fromTitle($projectTitle),
            ProjectStatus::Draft,
            $description !== null ? ProjectDescription::fromString($description) : null,
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
        ];
    }
}
