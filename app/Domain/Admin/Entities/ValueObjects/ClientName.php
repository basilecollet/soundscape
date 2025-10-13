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

        $normalized = self::safeRegexReplace('/\s+/', ' ', $normalized);
        $normalized = mb_convert_case($normalized, MB_CASE_TITLE, 'UTF-8');

        // Force uppercase after apostrophes for visual consistency
        $normalized = self::safeRegexReplaceCallback(
            '/\'(\p{L})/u',
            fn ($matches) => "'".mb_strtoupper($matches[1], 'UTF-8'),
            $normalized
        );

        return new self($normalized);
    }

    /**
     * Safe wrapper for preg_replace that guarantees a string return value.
     *
     * @throws InvalidClientNameException if regex processing fails
     */
    private static function safeRegexReplace(string $pattern, string $replacement, string $subject): string
    {
        $result = preg_replace($pattern, $replacement, $subject);

        if ($result === null) {
            throw InvalidClientNameException::processingError($subject);
        }

        return $result;
    }

    /**
     * Safe wrapper for preg_replace_callback that guarantees a string return value.
     *
     *
     * @throws InvalidClientNameException if regex processing fails
     */
    private static function safeRegexReplaceCallback(string $pattern, callable $callback, string $subject): string
    {
        $result = preg_replace_callback($pattern, $callback, $subject);

        if ($result === null) {
            throw InvalidClientNameException::processingError($subject);
        }

        return $result;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
