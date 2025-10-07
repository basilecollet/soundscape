<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities;

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;

final readonly class Project
{
    private function __construct(
        private ProjectTitle $title,
        private ProjectSlug $slug,
    ) {}

    public static function new(
        string $title,
    ): self {
        $projectTitle = ProjectTitle::fromString($title);

        return new self(
            $projectTitle,
            ProjectSlug::fromTitle($projectTitle),
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

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'title' => (string) $this->title,
            'slug' => (string) $this->slug,
        ];
    }
}
