<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

final readonly class UpdateProjectData
{
    public function __construct(
        public string $slug,
        public string $title,
        public ?string $description = null,
        public ?string $shortDescription = null,
        public ?string $clientName = null,
        public ?string $projectDate = null,
        public ?string $bandcampPlayer = null,
    ) {}

    /**
     * @param array{
     *     slug: string,
     *     title: string,
     *     description?: string,
     *     short_description?: string,
     *     client_name?: string,
     *     project_date?: string,
     *     bandcamp_player?: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            slug: $data['slug'],
            title: $data['title'],
            description: $data['description'] ?? null,
            shortDescription: $data['short_description'] ?? null,
            clientName: $data['client_name'] ?? null,
            projectDate: $data['project_date'] ?? null,
            bandcampPlayer: $data['bandcamp_player'] ?? null,
        );
    }

    /**
     * @return array{
     *     slug: string,
     *     title: string,
     *     description?: string|null,
     *     short_description?: string|null,
     *     client_name?: string|null,
     *     project_date?: string|null,
     *     bandcamp_player?: string|null,
     * }
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'short_description' => $this->shortDescription,
            'client_name' => $this->clientName,
            'project_date' => $this->projectDate,
            'bandcamp_player' => $this->bandcampPlayer,
        ];
    }
}
