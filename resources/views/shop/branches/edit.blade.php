<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-slate-950">Edit branch</h1>
            <form method="POST" action="{{ route('shop.branches.destroy', $branch) }}">
                @csrf
                @method('DELETE')
                <button class="btn-danger">Delete</button>
            </form>
        </div>
        <form method="POST" action="{{ route('shop.branches.update', $branch) }}" class="panel mt-6 p-6">
            @csrf
            @method('PUT')
            @include('shop.branches._form')
            <div class="mt-6 flex gap-3">
                <button class="btn-primary">Save changes</button>
                <a href="{{ route('shop.branches.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
