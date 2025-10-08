<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

final readonly class ClientName
{
    private function __construct(
        private string $name,
    ) {}

    public static function fromString(string $name): self
    {
        $normalized = trim($name);
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        $normalized = mb_convert_case($normalized, MB_CASE_TITLE, 'UTF-8');

        // Force uppercase after apostrophes for visual consistency
        $normalized = preg_replace_callback(
            '/\'(\p{L})/u',
            fn ($matches) => "'".mb_strtoupper($matches[1], 'UTF-8'),
            $normalized
        );

        return new self($normalized);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
