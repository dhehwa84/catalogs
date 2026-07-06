<x-app-layout>
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-950">Create catalogue</h1>
        <form method="POST" action="{{ route('shop.catalogues.store') }}" enctype="multipart/form-data" class="panel mt-6 p-6">
            @csrf
            @include('shop.catalogues._form')
            <div class="mt-6 flex gap-3">
                <button class="btn-primary">Save catalogue</button>
                <a href="{{ route('shop.catalogues.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
