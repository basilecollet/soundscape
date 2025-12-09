<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities;

use App\Domain\Portfolio\ValueObjects\PageField;

abstract class PortfolioPage
{
    /**
     * @param  array<PageField>  $fields
     */
    protected function __construct(
        protected readonly array $fields,
    ) {}

    /**
     * Check if page has minimum required content
     */
    abstract public function hasMinimumContent(): bool;

    /**
     * Get missing required fields for this page
     *
     * @return array<string>
     */
    abstract public function getMissingFields(): array;

    /**
     * Check if all specified field keys have non-empty content
     *
     * @param  array<string>  $requiredKeys
     */
    protected function allFieldsHaveContent(array $requiredKeys): bool
    {
        foreach ($requiredKeys as $key) {
            $field = $this->findField($key);

            if ($field === null || $field->isEmpty()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Find a field by its key
     */
    protected function findField(string $key): ?PageField
    {
        foreach ($this->fields as $field) {
            if ($field->getKey() === $key) {
                return $field;
            }
        }

        return null;
    }
}
