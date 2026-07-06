@php
    $catalogue = $catalogue ?? null;
    $selectedBranches = old('branch_ids', $catalogue?->branches?->pluck('id')->all() ?? []);
    $selectedAreas = old('area_ids', $catalogue?->areas?->pluck('id')->all() ?? []);
    $serverUploadMaxKb = (int) floor(ini_parse_quantity(ini_get('upload_max_filesize')) / 1024);
    $serverPostMaxKb = (int) floor(ini_parse_quantity(ini_get('post_max_size')) / 1024);
    $effectiveCoverMaxKb = min(config('catalogue.uploads.cover_image_max_kb'), $serverUploadMaxKb, $serverPostMaxKb);
    $effectiveFileMaxKb = min(config('catalogue.uploads.file_max_kb'), $serverUploadMaxKb, $serverPostMaxKb);
    $coverMaxMb = (int) floor($effectiveCoverMaxKb / 1024);
    $fileMaxMb = (int) floor($effectiveFileMaxKb / 1024);
    $serverLimitWarning = $effectiveFileMaxKb < config('catalogue.uploads.file_max_kb');
@endphp

@if($serverLimitWarning)
    <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        PHP is currently limiting uploads to {{ $fileMaxMb }} MB. Restart the Laravel server with the project upload settings to allow larger catalogue files.
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="label">Title</label>
        <input name="title" value="{{ old('title', $catalogue?->title) }}" class="field mt-1" required>
    </div>
    <div>
        <label class="label">Category</label>
        <select name="catalogue_category_id" class="field mt-1">
            <option value="">Select category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('catalogue_category_id', $catalogue?->catalogue_category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Status</label>
        <select name="status" class="field mt-1">
            @foreach(['draft' => 'Draft', 'pending_review' => 'Pending review', 'published' => 'Published', 'rejected' => 'Rejected', 'expired' => 'Expired', 'archived' => 'Archived'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $catalogue?->status ?? 'draft') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Valid from</label>
        <input type="date" name="valid_from" value="{{ old('valid_from', $catalogue?->valid_from?->format('Y-m-d')) }}" class="field mt-1" required>
    </div>
    <div>
        <label class="label">Valid to</label>
        <input type="date" name="valid_to" value="{{ old('valid_to', $catalogue?->valid_to?->format('Y-m-d')) }}" class="field mt-1" required>
    </div>
    <div>
        <label class="label">Cover image</label>
        <input type="file" name="cover_image" accept="image/*" class="field mt-1" {{ $catalogue ? '' : 'required' }}>
        <p class="mt-1 text-xs text-slate-500">JPG or PNG, up to {{ $coverMaxMb }} MB.</p>
        <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
    </div>
    <div>
        <label class="label">Catalogue file</label>
        <input type="file" name="catalogue_file" accept="application/pdf,image/*" class="field mt-1" {{ $catalogue ? '' : 'required' }}>
        <p class="mt-1 text-xs text-slate-500">PDF or image, up to {{ $fileMaxMb }} MB.</p>
        <x-input-error :messages="$errors->get('catalogue_file')" class="mt-2" />
    </div>
    <div class="sm:col-span-2">
        <label class="label">Description</label>
        <textarea name="description" rows="4" class="field mt-1">{{ old('description', $catalogue?->description) }}</textarea>
    </div>
    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
        <input type="checkbox" name="is_featured" value="1" class="rounded border-slate-300 text-emerald-700" @checked(old('is_featured', $catalogue?->is_featured))>
        Feature this catalogue
    </label>
</div>

<div class="mt-6 grid gap-5 lg:grid-cols-2">
    <div>
        <h2 class="text-lg font-bold text-slate-950">Covered branches</h2>
        <div class="mt-3 space-y-2 rounded-lg border border-slate-200 p-4">
            @forelse($branches as $branch)
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" class="rounded border-slate-300 text-emerald-700" @checked(in_array($branch->id, $selectedBranches))>
                    {{ $branch->name }}
                </label>
            @empty
                <p class="text-sm text-slate-500">Add branches before linking coverage.</p>
            @endforelse
        </div>
    </div>
    <div>
        <h2 class="text-lg font-bold text-slate-950">Promo areas</h2>
        <div class="mt-3 max-h-64 space-y-2 overflow-y-auto rounded-lg border border-slate-200 p-4">
            @foreach($suburbs as $suburb)
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="area_ids[]" value="{{ $suburb->id }}" class="rounded border-slate-300 text-emerald-700" @checked(in_array($suburb->id, $selectedAreas))>
                    {{ $suburb->name }} <span class="text-slate-400">{{ $suburb->city?->name }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
