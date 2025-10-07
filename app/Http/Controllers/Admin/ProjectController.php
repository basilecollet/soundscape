<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::all();

        return view('admin.project.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.project.create');
    }

    public function edit(Project $project): View
    {
        return view('admin.project.edit', compact('project'));
    }

    public function destroy(string $project): RedirectResponse
    {
        $projectModel = Project::findOrFail($project);
        $projectModel->delete();

        return to_route('admin.project.index');
    }
}
