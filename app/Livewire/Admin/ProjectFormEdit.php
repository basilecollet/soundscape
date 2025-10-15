<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Application\Admin\Commands\UpdateProject\UpdateProjectHandler;
use App\Application\Admin\DTOs\UpdateProjectData;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
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

    public $featuredImage = null;

    public $galleryImages = [];

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->title = $this->project->title;
        $this->description = $this->project->description ?? '';
        $this->shortDescription = $this->project->short_description ?? '';
        $this->clientName = $this->project->client_name ?? '';
        $this->projectDate = $this->project->project_date instanceof \Illuminate\Support\Carbon
            ? $this->project->project_date->format('Y-m-d')
            : '';
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'shortDescription' => ['nullable', 'string', 'max:500'],
            'clientName' => ['nullable', 'string', 'max:255'],
            'projectDate' => ['nullable', 'date'],
            'featuredImage' => ['nullable', 'image', 'max:10240'],
            'galleryImages.*' => ['nullable', 'image', 'max:10240'],
        ];
    }

    public function updatedFeaturedImage(): void
    {
        $this->validate([
            'featuredImage' => ['image', 'max:10240'],
        ]);
    }

    public function updatedGalleryImages(): void
    {
        $this->validate([
            'galleryImages.*' => ['image', 'max:10240'],
        ]);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function saveFeaturedImage(): void
    {
        if ($this->featuredImage) {
            $this->project->addMedia($this->featuredImage)
                ->toMediaCollection('featured');

            $this->featuredImage = null;
            $this->dispatch('featured-image-uploaded');
            session()->flash('success', 'Featured image uploaded successfully.');
        }
    }

    public function saveGalleryImages(): void
    {
        if (! empty($this->galleryImages)) {
            foreach ($this->galleryImages as $image) {
                $this->project->addMedia($image)
                    ->toMediaCollection('gallery');
            }

            $this->galleryImages = [];
            $this->dispatch('gallery-images-uploaded');
            session()->flash('success', 'Gallery images uploaded successfully.');
        }
    }

    public function deleteFeaturedImage(): void
    {
        $media = $this->project->getFirstMedia('featured');
        if ($media) {
            $media->delete();
            session()->flash('success', 'Featured image deleted successfully.');
        }
    }

    public function deleteGalleryImage(int $mediaId): void
    {
        $media = $this->project->getMedia('gallery')->firstWhere('id', $mediaId);
        if ($media) {
            $media->delete();
            session()->flash('success', 'Gallery image deleted successfully.');
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
        );

        $handler->handle($data);

        session()->flash('success', 'Project updated successfully.');

        $this->redirect(route('admin.project.edit', ['project' => $this->project->slug]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.project-form-edit');
    }
}
