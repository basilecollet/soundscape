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
        return new self(trim($name));
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
