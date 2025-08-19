<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
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

    public function updateContent(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        // Implémentation temporaire pour passer les tests
        $pageContent = \App\Models\PageContent::findOrFail($id);
        $pageContent->update([
            'content' => $request->input('content'),
            'title' => $request->input('title'),
        ]);
        
        return redirect()->route('admin.content.index');
    }
}