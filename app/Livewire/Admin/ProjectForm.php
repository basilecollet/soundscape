<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Application\Admin\Commands\CreateProject\CreateProjectHandler;
use App\Application\Admin\DTOs\CreateProjectData;
use App\Domain\Admin\Exceptions\DuplicateProjectSlugException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProjectForm extends Component
{
    public string $title = '';

    public string $description = '';

    public string $shortDescription = '';

    public string $clientName = '';

    public string $projectDate = '';

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
        ];
    }

    public function save(CreateProjectHandler $handler): void
    {
        $this->validate();

        try {
            $data = new CreateProjectData(
                title: $this->title,
                description: $this->description !== '' ? $this->description : null,
                shortDescription: $this->shortDescription !== '' ? $this->shortDescription : null,
                clientName: $this->clientName !== '' ? $this->clientName : null,
                projectDate: $this->projectDate !== '' ? $this->projectDate : null,
            );

            $slug = $handler->handle($data);

            session()->flash('success', 'Project created successfully.');

            $this->redirect(route('admin.project.edit', ['project' => (string) $slug]), navigate: true);
        } catch (DuplicateProjectSlugException $e) {
            $this->addError('title', $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.admin.project-form');
    }
}
