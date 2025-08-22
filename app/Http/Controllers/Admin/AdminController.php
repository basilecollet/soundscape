<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct() {}

    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    public function content(): View
    {
        return view('admin.content.index');
    }

    public function editContent(int $id): View
    {
        return view('admin.content.edit', ['contentId' => $id]);
    }
}
