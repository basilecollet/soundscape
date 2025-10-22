<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities\ValueObjects;

use App\Domain\Portfolio\Exceptions\InvalidProjectSlugException;
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

    public static function fromString(string $slug): self
    {
        if (! self::isValidSlug($slug)) {
            throw InvalidProjectSlugException::invalidFormat($slug);
        }

        return new self($slug);
    }

    private static function isValidSlug(string $slug): bool
    {
        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) === 1;
    }

    public function __toString(): string
    {
        return $this->slug;
    }
}
