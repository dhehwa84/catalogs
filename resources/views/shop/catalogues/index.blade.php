<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-950">Catalogues</h1>
                <p class="mt-1 text-slate-500">{{ $shop->name }}</p>
            </div>
            <a href="{{ route('shop.catalogues.create') }}" class="btn-primary">New catalogue</a>
        </div>
        <div class="mt-6 grid gap-5 lg:grid-cols-3">
            @forelse($catalogues as $catalogue)
                <div class="panel overflow-hidden">
                    <div class="aspect-[16/10] bg-slate-100">
                        @if($catalogue->cover_image)
                            <img src="{{ asset('storage/'.$catalogue->cover_image) }}" alt="{{ $catalogue->title }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="badge bg-slate-100 text-slate-700">{{ str_replace('_', ' ', ucfirst($catalogue->status)) }}</span>
                            <span class="text-xs font-semibold text-slate-500">{{ $catalogue->valid_to->format('d M Y') }}</span>
                        </div>
                        <h2 class="mt-3 font-bold text-slate-950">{{ $catalogue->title }}</h2>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('shop.catalogues.edit', $catalogue) }}" class="btn-secondary">Edit</a>
                            @if($catalogue->status === 'draft')
                                <form method="POST" action="{{ route('shop.catalogues.submit', $catalogue) }}">@csrf<button class="btn-secondary">Submit</button></form>
                            @endif
                            @if($catalogue->status !== 'published')
                                <form method="POST" action="{{ route('shop.catalogues.publish', $catalogue) }}">@csrf<button class="btn-primary">Publish</button></form>
                            @endif
                            <form method="POST" action="{{ route('shop.catalogues.archive', $catalogue) }}">@csrf<button class="btn-secondary">Archive</button></form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel p-8 text-center text-slate-500 lg:col-span-3">No catalogues yet.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
