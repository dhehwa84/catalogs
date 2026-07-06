<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="panel p-6">
            <h1 class="text-3xl font-bold text-slate-950">{{ $branch->name }}</h1>
            <p class="mt-2 text-slate-600">{{ $branch->address }}</p>
            <a href="{{ route('shop.branches.edit', $branch) }}" class="btn-primary mt-6">Edit branch</a>
        </div>
    </div>
</x-app-layout>
