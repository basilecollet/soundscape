<?php

declare(strict_types=1);

namespace App\Domain\Admin\Services;

final readonly class StringNormalizationService
{
    public function normalizeToNullable(?string $value): ?string
    {
        return $value !== null && trim($value) !== '' ? $value : null;
    }
}
