<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-emerald-700">Shop portal</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-950">{{ $shop->name }}</h1>
                <p class="mt-1 text-slate-500">Status: <span class="font-semibold">{{ ucfirst($shop->status) }}</span></p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('shop.catalogues.create') }}" class="btn-primary">New catalogue</a>
                <a href="{{ route('shop.branches.create') }}" class="btn-secondary">Add branch</a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="stat-card"><p class="muted">Active catalogues</p><p class="mt-2 text-3xl font-bold">{{ $activeCatalogues }}</p></div>
            <div class="stat-card"><p class="muted">Draft catalogues</p><p class="mt-2 text-3xl font-bold">{{ $draftCatalogues }}</p></div>
            <div class="stat-card"><p class="muted">Expired catalogues</p><p class="mt-2 text-3xl font-bold">{{ $expiredCatalogues }}</p></div>
            <div class="stat-card"><p class="muted">Branches</p><p class="mt-2 text-3xl font-bold">{{ $branches }}</p></div>
        </div>

        <div class="mt-8 grid gap-5 lg:grid-cols-4">
            <a href="{{ route('shop.profile.edit') }}" class="panel p-5 font-semibold hover:shadow-md">Manage profile</a>
            <a href="{{ route('shop.branches.index') }}" class="panel p-5 font-semibold hover:shadow-md">Branches and hours</a>
            <a href="{{ route('shop.catalogues.index') }}" class="panel p-5 font-semibold hover:shadow-md">Catalogues and promos</a>
            <a href="{{ route('shop.users.index') }}" class="panel p-5 font-semibold hover:shadow-md">Team users</a>
        </div>
    </div>
</x-app-layout>
