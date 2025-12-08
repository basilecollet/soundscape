<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Application\Admin\Commands\ArchiveProject\ArchiveProjectHandler;
use App\Application\Admin\Commands\DraftProject\DraftProjectHandler;
use App\Application\Admin\Commands\PublishProject\PublishProjectHandler;
use App\Application\Admin\Commands\UpdateProject\UpdateProjectHandler;
use App\Application\Admin\DTOs\UpdateProjectData;
use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Exceptions\ProjectCannotBeArchivedException;
use App\Domain\Admin\Exceptions\ProjectCannotBeDraftedException;
use App\Domain\Admin\Exceptions\ProjectCannotBePublishedException;
use App\Http\Requests\Admin\UpdateProjectMediaRequest;
use App\Models\Project;
use App\Rules\ValidBandcampEmbed;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProjectFormEdit extends Component
{
    use WithFileUploads;

    public Project $project;

    public string $title = '';

    public string $description = '';

    public string $shortDescription = '';

    public string $clientName = '';

    public string $projectDate = '';

    public string $bandcampPlayer = '';

    /** @var UploadedFile|null */
    public $featuredImage = null;

    /** @var array<int, UploadedFile> */
    public $galleryImages = [];

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->title = $this->project->title;
        $this->description = $this->project->description ?? '';
        $this->shortDescription = $this->project->short_description ?? '';
        $this->clientName = $this->project->client_name ?? '';
        $this->projectDate = $this->project->project_date instanceof Carbon
            ? $this->project->project_date->format('Y-m-d')
            : '';
        $this->bandcampPlayer = $this->project->bandcamp_player ?? '';
    }

    /**
     * @return array<string, array<int, string|ValidBandcampEmbed>>
     */
    public function rules(): array
    {
        $mediaRequest = new UpdateProjectMediaRequest;

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'shortDescription' => ['nullable', 'string', 'max:500'],
            'clientName' => ['nullable', 'string', 'max:255'],
            'projectDate' => ['nullable', 'date'],
            'bandcampPlayer' => ['nullable', 'string', 'max:10000', new ValidBandcampEmbed],
            ...$mediaRequest->rules(),
        ];
    }

    public function updatedFeaturedImage(): void
    {
        $mediaRequest = new UpdateProjectMediaRequest;
        $this->validate([
            'featuredImage' => $mediaRequest->rules()['featuredImage'],
        ]);
    }

    public function updatedGalleryImages(): void
    {
        $mediaRequest = new UpdateProjectMediaRequest;
        $this->validate([
            'galleryImages' => $mediaRequest->rules()['galleryImages'],
            'galleryImages.*' => $mediaRequest->rules()['galleryImages.*'],
        ]);
    }

    public function getFeaturedImagePreviewUrl(): ?string
    {
        if (! $this->featuredImage) {
            return null;
        }

        // Récupérer le chemin du fichier temporaire et construire l'URL publique
        return Storage::disk('public')->url(sprintf('livewire-tmp/%s', $this->featuredImage->getFilename()));
    }

    public function getGalleryImagePreviewUrl(?TemporaryUploadedFile $image): ?string
    {
        if (! $image) {
            return null;
        }

        // Récupérer le chemin du fichier temporaire et construire l'URL publique
        return Storage::disk('public')->url(sprintf('livewire-tmp/%s', $image->getFilename()));
    }

    public function saveFeaturedImage(): void
    {
        // Validation avec règles du Form Request
        $mediaRequest = new UpdateProjectMediaRequest;
        $this->validate([
            'featuredImage' => $mediaRequest->rules()['featuredImage'],
        ]);

        if (! $this->featuredImage) {
            return;
        }

        try {
            $this->project->addMedia($this->featuredImage)
                ->toMediaCollection('featured');

            $this->featuredImage = null;
            $this->project->refresh();
            $this->dispatch('featured-image-uploaded');
            session()->flash('success', __('admin.projects.form.featured_image.uploaded_successfully'));
        } catch (FileIsTooBig) {
            $this->addError('featuredImage', __('admin.projects.errors.file_too_large'));
        } catch (FileDoesNotExist) {
            $this->addError('featuredImage', __('admin.projects.errors.file_not_found'));
        } catch (FileCannotBeAdded) {
            $this->addError('featuredImage', __('admin.projects.errors.invalid_format'));
        }
    }

    public function saveGalleryImages(): void
    {
        // Validation avec règles du Form Request
        $mediaRequest = new UpdateProjectMediaRequest;
        $this->validate([
            'galleryImages' => $mediaRequest->rules()['galleryImages'],
            'galleryImages.*' => $mediaRequest->rules()['galleryImages.*'],
        ]);

        if (empty($this->galleryImages)) {
            return;
        }

        try {
            foreach ($this->galleryImages as $image) {
                $this->project->addMedia($image)
                    ->toMediaCollection('gallery');
            }

            $this->galleryImages = [];
            $this->project->refresh();
            $this->dispatch('gallery-images-uploaded');
            session()->flash('success', __('admin.projects.form.gallery_images.uploaded_successfully'));
        } catch (FileIsTooBig) {
            $this->addError('galleryImages.*', __('admin.projects.errors.file_too_large'));
        } catch (FileDoesNotExist) {
            $this->addError('galleryImages.*', __('admin.projects.errors.file_not_found'));
        } catch (FileCannotBeAdded) {
            $this->addError('galleryImages.*', __('admin.projects.errors.invalid_format'));
        }
    }

    public function deleteFeaturedImage(): void
    {
        $media = $this->project->getFirstMedia('featured');
        if ($media) {
            $media->delete();
            $this->project->refresh();
            session()->flash('success', __('admin.projects.form.featured_image.deleted_successfully'));
        }
    }

    public function deleteGalleryImage(int $mediaId): void
    {
        $media = $this->project->getMedia('gallery')->firstWhere('id', $mediaId);
        if ($media) {
            $media->delete();
            $this->project->refresh();
            session()->flash('success', __('admin.projects.form.gallery_images.deleted_successfully'));
        }
    }

    public function save(UpdateProjectHandler $handler): void
    {
        $this->validate();

        $data = new UpdateProjectData(
            slug: $this->project->slug,
            title: $this->title,
            description: $this->description !== '' ? $this->description : null,
            shortDescription: $this->shortDescription !== '' ? $this->shortDescription : null,
            clientName: $this->clientName !== '' ? $this->clientName : null,
            projectDate: $this->projectDate !== '' ? $this->projectDate : null,
            bandcampPlayer: $this->bandcampPlayer !== '' ? $this->bandcampPlayer : null,
        );

        $handler->handle($data);

        session()->flash('success', __('admin.projects.updated_successfully'));

        $this->redirect(route('admin.project.edit', ['project' => $this->project->slug]), navigate: true);
    }

    public function publish(PublishProjectHandler $handler): void
    {
        try {
            $handler->handle($this->project->slug);
            $this->project->refresh();
            $this->dispatch('close-publish-modal');
            session()->flash('success', __('admin.projects.published_successfully'));
        } catch (ProjectCannotBePublishedException $e) {
            if ($e->hasMissingDescription()) {
                session()->flash('error', __('domain.project.cannot_publish_missing_description'));
            } else {
                session()->flash('error', __('domain.project.cannot_publish_invalid_status', [
                    'status' => $e->getStatus()->value ?? 'unknown',
                ]));
            }
        }
    }

    public function archive(ArchiveProjectHandler $handler): void
    {
        try {
            $handler->handle($this->project->slug);
            $this->project->refresh();
            $this->dispatch('close-archive-modal');
            session()->flash('success', __('admin.projects.archived_successfully'));
        } catch (ProjectCannotBeArchivedException $e) {
            session()->flash('error', __('domain.project.cannot_be_archived', [
                'status' => $e->getStatus()->value,
            ]));
        }
    }

    public function draft(DraftProjectHandler $handler): void
    {
        try {
            $handler->handle($this->project->slug);
            $this->project->refresh();
            $this->dispatch('close-draft-modal');
            session()->flash('success', __('admin.projects.drafted_successfully'));
        } catch (ProjectCannotBeDraftedException $e) {
            session()->flash('error', __('domain.project.already_draft'));
        }
    }

    /**
     * Determine if the project can be published.
     *
     * Only draft projects can be published.
     */
    public function getCanPublishProperty(): bool
    {
        return $this->project->status === ProjectStatus::Draft;
    }

    /**
     * Determine if the project can be archived.
     *
     * Only published projects can be archived.
     */
    public function getCanArchiveProperty(): bool
    {
        return $this->project->status === ProjectStatus::Published;
    }

    /**
     * Determine if the project can be set back to draft.
     *
     * Both published and archived projects can be set back to draft.
     */
    public function getCanDraftProperty(): bool
    {
        return $this->project->status !== ProjectStatus::Draft;
    }

    public function render(): View
    {
        return view('livewire.admin.project-form-edit');
    }
}
