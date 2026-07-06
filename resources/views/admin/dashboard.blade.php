<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-950">Platform admin</h1>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="stat-card"><p class="muted">Pending shops</p><p class="mt-2 text-3xl font-bold">{{ $pendingShops }}</p></div>
            <div class="stat-card"><p class="muted">Published catalogues</p><p class="mt-2 text-3xl font-bold">{{ $activeCatalogues }}</p></div>
            <a href="{{ route('admin.shops.index') }}" class="stat-card font-semibold">Manage shops</a>
            <a href="{{ route('admin.catalogues.index') }}" class="stat-card font-semibold">Moderate catalogues</a>
        </div>
        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <div class="panel p-5">
                <h2 class="text-lg font-bold">Recent shops</h2>
                <div class="mt-4 space-y-3">
                    @foreach($shops as $shop)
                        <a href="{{ route('admin.shops.show', $shop) }}" class="block rounded-lg border border-slate-200 p-3 hover:bg-slate-50">{{ $shop->name }} <span class="text-sm text-slate-500">({{ $shop->status }})</span></a>
                    @endforeach
                </div>
            </div>
            <div class="panel p-5">
                <h2 class="text-lg font-bold">Recent catalogues</h2>
                <div class="mt-4 space-y-3">
                    @foreach($catalogues as $catalogue)
                        <a href="{{ route('admin.catalogues.show', $catalogue) }}" class="block rounded-lg border border-slate-200 p-3 hover:bg-slate-50">{{ $catalogue->title }} <span class="text-sm text-slate-500">({{ $catalogue->status }})</span></a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
