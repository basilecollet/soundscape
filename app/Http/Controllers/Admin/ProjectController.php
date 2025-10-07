<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
}
