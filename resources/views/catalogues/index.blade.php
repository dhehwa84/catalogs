<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-950">Browse catalogues</h1>
                <p class="mt-1 text-slate-500">Filter by Zimbabwean shop, branch city, promo area, date, or category.</p>
            </div>
            <a href="{{ route('catalogues.index') }}" class="btn-secondary">Reset filters</a>
        </div>

        <form method="GET" class="panel p-5">
            <div class="grid gap-4 md:grid-cols-4">
                <input name="q" value="{{ request('q') }}" placeholder="Search catalogue or shop" class="field md:col-span-2">
                <select name="shop_id" class="field">
                    <option value="">All shops</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" @selected(request('shop_id') == $shop->id)>{{ $shop->name }}</option>
                    @endforeach
                </select>
                <select name="catalogue_category_id" class="field">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('catalogue_category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="city_id" class="field">
                    <option value="">All cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" @selected(request('city_id') == $city->id)>{{ $city->name }}</option>
                    @endforeach
                </select>
                <select name="suburb_id" class="field">
                    <option value="">All areas</option>
                    @foreach($suburbs as $suburb)
                        <option value="{{ $suburb->id }}" @selected(request('suburb_id') == $suburb->id)>{{ $suburb->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="valid_from" value="{{ request('valid_from') }}" class="field">
                <input type="date" name="valid_to" value="{{ request('valid_to') }}" class="field">
            </div>
            <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap gap-4 text-sm font-medium text-slate-700">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="show_expired" value="1" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600" @checked(request()->boolean('show_expired'))>
                        Include expired
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="expired_only" value="1" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600" @checked(request()->boolean('expired_only'))>
                        Expired only
                    </label>
                </div>
                <button class="btn-primary">Apply filters</button>
            </div>
        </form>

        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($catalogues as $catalogue)
                <a href="{{ route('catalogues.show', [$catalogue->shop->slug, $catalogue->slug]) }}" class="panel overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="aspect-[16/10] bg-slate-100">
                        @if($catalogue->cover_image)
                            <img src="{{ asset('storage/'.$catalogue->cover_image) }}" alt="{{ $catalogue->title }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="badge bg-emerald-50 text-emerald-700">{{ $catalogue->category?->name ?? 'Promotion' }}</span>
                            @if($catalogue->valid_to->lt(today()))
                                <span class="badge bg-rose-50 text-rose-700">Expired</span>
                            @else
                                <span class="badge bg-slate-100 text-slate-700">Active</span>
                            @endif
                        </div>
                        <h2 class="mt-3 text-lg font-bold text-slate-950">{{ $catalogue->title }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $catalogue->shop->name }}</p>
                        <p class="mt-4 text-sm font-semibold text-slate-700">Valid {{ $catalogue->valid_from->format('d M') }} - {{ $catalogue->valid_to->format('d M Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="panel p-8 text-center text-slate-500 sm:col-span-2 lg:col-span-3">No catalogues match these filters.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $catalogues->links() }}
        </div>
    </div>
</x-app-layout>
