<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Portfolio;

use App\Domain\Portfolio\Entities\Image;
use App\Domain\Portfolio\Entities\PublishedProject;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDate;
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
            ->orderBy('project_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (ProjectDatabase $model) {
                return $this->reconstitutePublishedProject($model);
            });
    }

    private function reconstitutePublishedProject(ProjectDatabase $projectDatabase): PublishedProject
    {
        // Handle project_date
        $projectDate = null;
        if ($projectDatabase->project_date !== null) {
            $projectDate = $projectDatabase->project_date instanceof \Carbon\Carbon
                ? ProjectDate::fromCarbon($projectDatabase->project_date)
                : ProjectDate::fromString($projectDatabase->project_date);
        }

        // Load media relations
        $projectDatabase->load('media');

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

        return PublishedProject::reconstitute(
            title: ProjectTitle::fromString($projectDatabase->title),
            slug: ProjectSlug::fromString($projectDatabase->slug),
            shortDescription: $projectDatabase->short_description !== null ? ProjectShortDescription::fromString($projectDatabase->short_description) : null,
            projectDate: $projectDate,
            featuredImage: $featuredImage,
        );
    }
}
