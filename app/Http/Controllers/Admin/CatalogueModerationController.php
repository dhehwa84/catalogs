<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Services\CatalogueSubscriberNotifier;
use Illuminate\Http\Request;

class CatalogueModerationController extends Controller
{
    public function __construct(protected CatalogueSubscriberNotifier $subscriberNotifier)
    {
    }

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

        $wasPublished = $catalogue->status === 'published';

        if ($data['status'] === 'published' && ! $catalogue->published_at) {
            $data['published_at'] = now();
        }

        $catalogue->update($data);

        if (! $wasPublished && $catalogue->status === 'published') {
            $this->subscriberNotifier->notifyIfNeeded($catalogue);
        }

        return back()->with('status', 'Catalogue updated.');
    }
}
