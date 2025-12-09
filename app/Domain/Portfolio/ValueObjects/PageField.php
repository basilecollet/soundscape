<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\ValueObjects;

final readonly class PageField
{
    private function __construct(
        private string $key,
        private ?string $content,
    ) {}

    public static function fromKeyAndContent(string $key, ?string $content): self
    {
        return new self($key, $content);
    }

    public function isEmpty(): bool
    {
        return $this->content === null || trim($this->content) === '';
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function __toString(): string
    {
        return $this->content ?? '';
    }
}
