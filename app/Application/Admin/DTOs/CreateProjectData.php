<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

final readonly class CreateProjectData
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $shortDescription = null,
        public ?string $clientName = null,
        public ?string $projectDate = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            shortDescription: $data['short_description'] ?? null,
            clientName: $data['client_name'] ?? null,
            projectDate: $data['project_date'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'short_description' => $this->shortDescription,
            'client_name' => $this->clientName,
            'project_date' => $this->projectDate,
        ];
    }
}