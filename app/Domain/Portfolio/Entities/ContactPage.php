<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities;

use App\Domain\Portfolio\ValueObjects\PageField;

final class ContactPage extends PortfolioPage
{
    private const REQUIRED_FIELDS = [
        'contact_title',
        'contact_subtitle',
        'contact_description',
        'contact_email',
        'contact_phone',
        'contact_location',
    ];

    /**
     * @param  array<PageField>  $fields
     */
    private function __construct(array $fields)
    {
        parent::__construct($fields);
    }

    /**
     * @param  array<PageField>  $fields
     */
    public static function reconstitute(array $fields): self
    {
        return new self($fields);
    }

    public function hasMinimumContent(): bool
    {
        return $this->allFieldsHaveContent(self::REQUIRED_FIELDS);
    }

    /**
     * @return array<string>
     */
    public function getMissingFields(): array
    {
        $missing = [];

        foreach (self::REQUIRED_FIELDS as $key) {
            if ($this->isFieldMissing($key)) {
                $missing[] = $key;
            }
        }

        return $missing;
    }

    private function isFieldMissing(string $key): bool
    {
        $field = $this->findField($key);

        return $field === null || $field->isEmpty();
    }
}
