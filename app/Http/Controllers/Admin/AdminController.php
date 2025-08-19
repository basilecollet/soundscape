<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Application\Admin\DTOs\ContentUpdateData;
use App\Application\Admin\Services\ContentManagementService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        private readonly ContentManagementService $contentManagementService
    ) {}

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
        $validated = $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        $updateData = ContentUpdateData::fromArray([
            'id' => $id,
            'content' => $validated['content'],
            'title' => $validated['title'] ?? null,
        ]);

        $this->contentManagementService->updateContent($updateData);

        return redirect()->route('admin.content.index');
    }
}
