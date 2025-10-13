<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Application\Admin\Queries\GetProjects\GetProjectsHandler;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProjectList extends Component
{
    public function render(GetProjectsHandler $getProjectsHandler): View
    {
        $projects = $getProjectsHandler->handle();

        return view('livewire.admin.project-list', [
            'projects' => $projects,
        ]);
    }
}
