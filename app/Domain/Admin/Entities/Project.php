<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities;

use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;

final readonly class Project
{
    private function __construct(
        private ProjectTitle $title,
    ) {
    }

    public static function new(
        string $title,
    ): self {
        return new self(
            ProjectTitle::fromString($title),
        );
    }

    public function getTitle(): ProjectTitle
    {
        return $this->title;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'title' => (string) $this->title,
        ];
    }
}
