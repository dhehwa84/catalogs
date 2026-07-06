<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use App\Models\Shop;
use App\Models\Suburb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $shop = $this->resolveShop($request);
        $catalogues = $shop->catalogues()->with(['category'])->latest()->get();

        return view('shop.catalogues.index', compact('shop', 'catalogues'));
    }

    public function create(Request $request)
    {
        $shop = $this->resolveShop($request);
        $categories = CatalogueCategory::query()->orderBy('name')->get();
        $branches = $shop->branches()->orderBy('name')->get();
        $suburbs = Suburb::query()->with('city')->orderBy('name')->get();

        return view('shop.catalogues.create', compact('shop', 'categories', 'branches', 'suburbs'));
    }

    public function store(Request $request)
    {
        $shop = $this->resolveShop($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'catalogue_category_id' => ['nullable', 'exists:catalogue_categories,id'],
            'valid_from' => ['required', 'date'],
            'valid_to' => ['required', 'date', 'after_or_equal:valid_from'],
            'cover_image' => ['required', 'image', 'max:'.config('catalogue.uploads.cover_image_max_kb')],
            'catalogue_file' => ['required', 'file', 'max:'.config('catalogue.uploads.file_max_kb')],
            'status' => ['nullable', 'in:draft,pending_review,published,rejected,expired,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'branch_ids' => ['nullable', 'array'],
            'branch_ids.*' => ['exists:branches,id'],
            'area_ids' => ['nullable', 'array'],
            'area_ids.*' => ['exists:suburbs,id'],
        ], $this->uploadValidationMessages());

        $branchIds = $shop->branches()->whereIn('id', $data['branch_ids'] ?? [])->pluck('id');
        $areaIds = $data['area_ids'] ?? [];
        unset($data['branch_ids'], $data['area_ids'], $data['catalogue_file']);

        $data['shop_id'] = $shop->id;
        $data['created_by'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($shop, $data['title']);
        $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        $data['file_path'] = $request->file('catalogue_file')->store('catalogues', 'public');
        $data['file_type'] = $request->file('catalogue_file')->extension();
        $data['status'] = $data['status'] ?? 'draft';
        $data['is_featured'] = $request->boolean('is_featured');

        $catalogue = $shop->catalogues()->create($data);
        $catalogue->branches()->sync($branchIds);
        $catalogue->areas()->sync($areaIds);

        return redirect()->route('shop.catalogues.index')->with('status', 'Catalogue created.');
    }

    public function show(Request $request, Catalogue $catalogue)
    {
        abort_unless($catalogue->shop_id === $this->resolveShop($request)->id, 404);
        $catalogue->load(['category', 'branches.city', 'branches.suburb', 'areas.city']);

        return view('shop.catalogues.show', compact('catalogue'));
    }

    public function edit(Request $request, Catalogue $catalogue)
    {
        abort_unless($catalogue->shop_id === $this->resolveShop($request)->id, 404);
        $shop = $this->resolveShop($request);
        $categories = CatalogueCategory::query()->orderBy('name')->get();
        $branches = $shop->branches()->orderBy('name')->get();
        $suburbs = Suburb::query()->with('city')->orderBy('name')->get();
        $catalogue->load(['branches', 'areas']);

        return view('shop.catalogues.edit', compact('shop', 'catalogue', 'categories', 'branches', 'suburbs'));
    }

    public function update(Request $request, Catalogue $catalogue)
    {
        abort_unless($catalogue->shop_id === $this->resolveShop($request)->id, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'catalogue_category_id' => ['nullable', 'exists:catalogue_categories,id'],
            'valid_from' => ['required', 'date'],
            'valid_to' => ['required', 'date', 'after_or_equal:valid_from'],
            'cover_image' => ['nullable', 'image', 'max:'.config('catalogue.uploads.cover_image_max_kb')],
            'catalogue_file' => ['nullable', 'file', 'max:'.config('catalogue.uploads.file_max_kb')],
            'status' => ['nullable', 'in:draft,pending_review,published,rejected,expired,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'branch_ids' => ['nullable', 'array'],
            'branch_ids.*' => ['exists:branches,id'],
            'area_ids' => ['nullable', 'array'],
            'area_ids.*' => ['exists:suburbs,id'],
        ], $this->uploadValidationMessages());

        $branchIds = $this->resolveShop($request)->branches()->whereIn('id', $data['branch_ids'] ?? [])->pluck('id');
        $areaIds = $data['area_ids'] ?? [];
        unset($data['branch_ids'], $data['area_ids'], $data['cover_image'], $data['catalogue_file']);

        $data['slug'] = $this->uniqueSlug($catalogue->shop, $data['title'], $catalogue);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('cover_image')) {
            if ($catalogue->cover_image) {
                Storage::disk('public')->delete($catalogue->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('catalogue_file')) {
            if ($catalogue->file_path) {
                Storage::disk('public')->delete($catalogue->file_path);
            }

            $data['file_path'] = $request->file('catalogue_file')->store('catalogues', 'public');
            $data['file_type'] = $request->file('catalogue_file')->extension();
        }

        $catalogue->update($data);
        $catalogue->branches()->sync($branchIds);
        $catalogue->areas()->sync($areaIds);

        return redirect()->route('shop.catalogues.index')->with('status', 'Catalogue updated.');
    }

    public function submit(Request $request, Catalogue $catalogue)
    {
        abort_unless($catalogue->shop_id === $this->resolveShop($request)->id, 404);
        $catalogue->update(['status' => 'pending_review']);

        return back()->with('status', 'Catalogue submitted for review.');
    }

    public function publish(Request $request, Catalogue $catalogue)
    {
        abort_unless($catalogue->shop_id === $this->resolveShop($request)->id, 404);
        $catalogue->update(['status' => 'published', 'published_at' => now()]);

        return back()->with('status', 'Catalogue published.');
    }

    public function archive(Request $request, Catalogue $catalogue)
    {
        abort_unless($catalogue->shop_id === $this->resolveShop($request)->id, 404);
        $catalogue->update(['status' => 'archived']);

        return back()->with('status', 'Catalogue archived.');
    }

    public function destroy(Request $request, Catalogue $catalogue)
    {
        abort_unless($catalogue->shop_id === $this->resolveShop($request)->id, 404);

        Storage::disk('public')->delete(array_filter([$catalogue->cover_image, $catalogue->file_path]));
        $catalogue->delete();

        return redirect()->route('shop.catalogues.index')->with('status', 'Catalogue removed.');
    }

    protected function resolveShop(Request $request): Shop
    {
        $shop = Shop::query()->whereHas('users', fn ($q) => $q->where('users.id', $request->user()->id))->first();

        if (! $shop) {
            $shop = Shop::query()->where('created_by', $request->user()->id)->firstOrFail();
        }

        return $shop;
    }

    protected function uniqueSlug(Shop $shop, string $title, ?Catalogue $ignore = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while ($shop->catalogues()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function uploadValidationMessages(): array
    {
        $uploadMax = ini_get('upload_max_filesize') ?: 'the PHP upload limit';
        $postMax = ini_get('post_max_size') ?: 'the PHP post limit';

        return [
            'cover_image.uploaded' => "The cover image could not be uploaded. PHP is currently set to upload_max_filesize={$uploadMax} and post_max_size={$postMax}.",
            'catalogue_file.uploaded' => "The catalogue file could not be uploaded. PHP is currently set to upload_max_filesize={$uploadMax} and post_max_size={$postMax}.",
        ];
    }
}
