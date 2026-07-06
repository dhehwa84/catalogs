<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="panel overflow-hidden">
            @if($catalogue->cover_image)
                <img src="{{ asset('storage/'.$catalogue->cover_image) }}" alt="{{ $catalogue->title }}" class="max-h-[420px] w-full object-cover">
            @endif
            <div class="p-6">
                <h1 class="text-3xl font-bold text-slate-950">{{ $catalogue->title }}</h1>
                <p class="mt-3 text-slate-600">{{ $catalogue->description }}</p>
                <div class="mt-5 flex gap-3">
                    <a href="{{ route('shop.catalogues.edit', $catalogue) }}" class="btn-primary">Edit catalogue</a>
                    @if($catalogue->file_path)
                        <a href="{{ asset('storage/'.$catalogue->file_path) }}" target="_blank" class="btn-secondary">Open file</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
