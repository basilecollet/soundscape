<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Application\Admin\Commands\UpdateProject\UpdateProjectHandler;
use App\Application\Admin\DTOs\UpdateProjectData;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProjectFormEdit extends Component
{
    public Project $project;

    public string $title = '';

    public string $description = '';

    public string $shortDescription = '';

    public string $clientName = '';

    public string $projectDate = '';

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->title = $this->project->title;
        $this->description = $this->project->description ?? '';
        $this->shortDescription = $this->project->short_description ?? '';
        $this->clientName = $this->project->client_name ?? '';
        $this->projectDate = $this->project->project_date?->format('Y-m-d') ?? '';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'shortDescription' => ['nullable', 'string', 'max:500'],
            'clientName' => ['nullable', 'string', 'max:255'],
            'projectDate' => ['nullable', 'date'],
        ];
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
