<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

use App\Domain\Admin\Exceptions\InvalidClientNameException;

final readonly class ClientName
{
    private function __construct(
        private string $name,
    ) {}

    public static function fromString(string $name): self
    {
        $normalized = trim($name);

        if (empty($normalized)) {
            throw InvalidClientNameException::empty();
        }

        if (mb_strlen($normalized) > 255) {
            throw InvalidClientNameException::tooLong(mb_strlen($normalized));
        }

        $normalized = (string) preg_replace('/\s+/', ' ', $normalized);
        $normalized = mb_convert_case($normalized, MB_CASE_TITLE, 'UTF-8');

        // Force uppercase after apostrophes for visual consistency
        $normalized = (string) preg_replace_callback(
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
