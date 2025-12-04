<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Admin;

use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Entities\Image;
use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\BandcampPlayer;
use App\Domain\Admin\Entities\ValueObjects\ClientName;
use App\Domain\Admin\Entities\ValueObjects\ProjectDate;
use App\Domain\Admin\Entities\ValueObjects\ProjectDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;
use App\Domain\Admin\Exceptions\ProjectNotFoundException;
use App\Domain\Admin\Repositories\ProjectRepository;
use App\Models\Project as ProjectDatabase;
use Illuminate\Support\Collection;

class ProjectDatabaseRepository implements ProjectRepository
{
    public function store(Project $project): void
    {
        ProjectDatabase::updateOrCreate(
            ['slug' => (string) $project->getSlug()],
            $project->toArray()
        );
    }

    /**
     * @return Collection<int, Project>
     */
    public function getAll(): Collection
    {
        return ProjectDatabase::with('media')->get()->map(function (ProjectDatabase $model) {
            return $this->reconstituteProject($model);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findBySlug(ProjectSlug $slug): ?Project
    {
        $model = ProjectDatabase::with('media')->where('slug', (string) $slug)->first();

        if ($model === null) {
            return null;
        }

        return $this->reconstituteProject($model);
    }

    /**
     * {@inheritdoc}
     */
    public function getBySlug(ProjectSlug $slug): Project
    {
        $project = $this->findBySlug($slug);

        if ($project === null) {
            throw ProjectNotFoundException::forSlug($slug);
        }

        return $project;
    }

    public function delete(ProjectSlug $slug): void
    {
        $deleted = ProjectDatabase::where('slug', (string) $slug)->delete();

        if ($deleted === 0) {
            throw ProjectNotFoundException::forSlug($slug);
        }
    }

    private function reconstituteProject(ProjectDatabase $projectDatabase): Project
    {
        // Handle status: it might already be cast to ProjectStatus enum by Eloquent
        $status = $projectDatabase->status instanceof ProjectStatus
            ? $projectDatabase->status
            : ProjectStatus::from($projectDatabase->status);

        // Handle project_date: it might already be cast to Carbon by Eloquent
        $projectDate = null;
        if ($projectDatabase->project_date !== null) {
            $projectDate = $projectDatabase->project_date instanceof \Carbon\Carbon
                ? ProjectDate::fromCarbon($projectDatabase->project_date)
                : ProjectDate::fromString((string) $projectDatabase->project_date);
        }

        // Transform featured media to Image entity
        $featuredImage = null;
        $featuredMedia = $projectDatabase->getFirstMedia('featured');
        if ($featuredMedia !== null) {
            $featuredImage = new Image(
                originalUrl: $featuredMedia->getUrl(),
                thumbUrl: $featuredMedia->getUrl('thumb'),
                webUrl: $featuredMedia->getUrl('web'),
                previewUrl: $featuredMedia->getUrl('preview'),
                alt: $featuredMedia->getCustomProperty('alt')
            );
        }

        // Transform gallery media to array of Image entities
        $galleryImages = $projectDatabase->getMedia('gallery')->map(function ($media) {
            return new Image(
                originalUrl: $media->getUrl(),
                thumbUrl: $media->getUrl('thumb'),
                webUrl: $media->getUrl('web'),
                previewUrl: $media->getUrl('preview'),
                alt: $media->getCustomProperty('alt')
            );
        })->toArray();

        return Project::reconstitute(
            title: ProjectTitle::fromString($projectDatabase->title),
            slug: ProjectSlug::fromString($projectDatabase->slug),
            status: $status,
            description: $projectDatabase->description !== null ? ProjectDescription::fromString($projectDatabase->description) : null,
            shortDescription: $projectDatabase->short_description !== null ? ProjectShortDescription::fromString($projectDatabase->short_description) : null,
            clientName: $projectDatabase->client_name !== null ? ClientName::fromString($projectDatabase->client_name) : null,
            projectDate: $projectDate,
            bandcampPlayer: $projectDatabase->bandcamp_player !== null ? BandcampPlayer::fromString($projectDatabase->bandcamp_player) : null,
            featuredImage: $featuredImage,
            galleryImages: $galleryImages,
        );
    }
}
