<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Portfolio;

use App\Domain\Portfolio\Entities\Image;
use App\Domain\Portfolio\Entities\PublishedProject;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDate;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectSlug;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectTitle;
use App\Domain\Portfolio\Repositories\ProjectRepository;
use App\Models\Project as ProjectDatabase;
use Illuminate\Support\Collection;

class ProjectDatabaseRepository implements ProjectRepository
{
    /**
     * @return Collection<int, PublishedProject>
     */
    public function getAll(): Collection
    {
        return ProjectDatabase::where('status', 'published')
            ->with('media')
            ->orderBy('project_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (ProjectDatabase $model) {
                return $this->reconstitutePublishedProject($model);
            });
    }

    public function getBySlug(string $slug): PublishedProject
    {
        $projectDatabase = ProjectDatabase::where('slug', $slug)
            ->where('status', 'published')
            ->with('media')
            ->firstOrFail();

        return $this->reconstitutePublishedProject($projectDatabase);
    }

    private function reconstitutePublishedProject(ProjectDatabase $projectDatabase): PublishedProject
    {
        // Handle project_date
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
                webUrl: $featuredMedia->getUrl('web'),
                thumbUrl: $featuredMedia->getUrl('thumb'),
                alt: $featuredMedia->getCustomProperty('alt')
            );
        }

        // Transform gallery media to Image entities
        $galleryImages = [];
        $galleryMedia = $projectDatabase->getMedia('gallery');
        foreach ($galleryMedia as $media) {
            $galleryImages[] = new Image(
                webUrl: $media->getUrl('web'),
                thumbUrl: $media->getUrl('thumb'),
                alt: $media->getCustomProperty('alt')
            );
        }

        // Description is required for PublishedProject
        $description = ProjectDescription::fromString($projectDatabase->description ?? '');

        return PublishedProject::reconstitute(
            title: ProjectTitle::fromString($projectDatabase->title),
            slug: ProjectSlug::fromString($projectDatabase->slug),
            description: $description,
            shortDescription: $projectDatabase->short_description !== null ? ProjectShortDescription::fromString($projectDatabase->short_description) : null,
            projectDate: $projectDate,
            featuredImage: $featuredImage,
            galleryImages: $galleryImages,
        );
    }
}
