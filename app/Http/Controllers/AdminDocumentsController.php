<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(): View
    {
        $documents = Document::query()
            ->with(['uploadedByAdmin', 'media'])
            ->latest()
            ->paginate(25);

        return view('admin.documents.index', compact('documents'));
    }

    public function create(): View
    {
        return view('admin.documents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => ['required', 'file', 'max:51200'], // 50MB
        ]);

        $document = new Document();
        $document->title = $validated['title'];
        $document->description = $validated['description'] ?? null;
        $document->uploaded_by_admin_id = auth('admin')->id();
        $document->save();

        $document
            ->addMediaFromRequest('file')
            ->toMediaCollection('file');

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Document uploaded.');
    }

    public function download(Document $document): RedirectResponse
    {
        $media = $document->getFirstMedia('file');

        abort_unless($media, 404);

        if (method_exists($media, 'getTemporaryUrl')) {
            try {
                return redirect()->away($media->getTemporaryUrl(now()->addMinutes(30)));
            } catch (\Throwable) {
                // Fall through to public URL.
            }
        }

        return redirect()->away($media->getUrl());
    }

    public function destroy(Document $document): RedirectResponse
    {
        $document->clearMediaCollection('file');
        $document->delete();

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Document deleted.');
    }
}

