<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

use Illuminate\Support\Str;

final readonly class ProjectSlug
{
    private function __construct(
        private string $slug,
    ) {}

    public static function fromTitle(ProjectTitle $title): self
    {
        $slug = Str::slug((string) $title, '-');

        return new self($slug);
    }

    public function __toString(): string
    {
        return $this->slug;
    }
}
