<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use Illuminate\Http\Request;

class CatalogueModerationController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        $catalogues = Catalogue::query()->latest()->paginate(20);

        return view('admin.catalogues.index', compact('catalogues'));
    }

    public function show(Request $request, Catalogue $catalogue)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        return view('admin.catalogues.show', compact('catalogue'));
    }

    public function update(Request $request, Catalogue $catalogue)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required', 'in:draft,pending_review,published,rejected,expired,archived'],
            'review_notes' => ['nullable', 'string'],
        ]);

        $catalogue->update($data);

        return back()->with('status', 'Catalogue updated.');
    }
}
