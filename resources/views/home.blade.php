<x-app-layout>
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1.1fr_.9fr] lg:px-8 lg:py-16">
            <div class="flex flex-col justify-center">
                <p class="text-sm font-bold uppercase tracking-wide text-emerald-700">Zimbabwe retail catalogues</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Find current specials from Zimbabwean shops in one place.</h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">Browse active catalogues, check validity dates, confirm branch coverage, and get directions before you leave home.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('catalogues.index') }}" class="btn-primary">Browse catalogues</a>
                    @auth
                        <a href="{{ route('shop.dashboard') }}" class="btn-secondary">Open shop portal</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-secondary">Register your shop</a>
                    @endauth
                </div>
            </div>

            <div class="panel overflow-hidden">
                <div class="bg-slate-950 px-5 py-4 text-white">
                    <p class="text-sm font-semibold">Live catalogue window</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($catalogues->take(4) as $catalogue)
                        <a href="{{ route('catalogues.show', [$catalogue->shop->slug, $catalogue->slug]) }}" class="flex items-center gap-4 p-5 transition hover:bg-slate-50">
                            <div class="h-20 w-16 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                @if($catalogue->cover_image)
                                    <img src="{{ asset('storage/'.$catalogue->cover_image) }}" alt="{{ $catalogue->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="grid h-full place-items-center text-xs font-bold text-slate-400">PDF</div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-slate-950">{{ $catalogue->title }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $catalogue->shop->name }} · valid until {{ $catalogue->valid_to->format('d M Y') }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-sm text-slate-500">No active catalogues yet. Seed the database to load demo Zimbabwean retailers.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-950">Latest active catalogues</h2>
                <p class="mt-1 text-sm text-slate-500">Expired promotions are hidden unless a shopper explicitly filters for them.</p>
            </div>
            <a href="{{ route('catalogues.index') }}" class="btn-secondary">View all</a>
        </div>

        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($catalogues as $catalogue)
                <a href="{{ route('catalogues.show', [$catalogue->shop->slug, $catalogue->slug]) }}" class="panel overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="aspect-[4/3] bg-slate-100">
                        @if($catalogue->cover_image)
                            <img src="{{ asset('storage/'.$catalogue->cover_image) }}" alt="{{ $catalogue->title }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="badge bg-emerald-50 text-emerald-700">{{ $catalogue->category?->name ?? 'Promotion' }}</span>
                        <h3 class="mt-3 line-clamp-2 font-bold text-slate-950">{{ $catalogue->title }}</h3>
                        <p class="mt-2 text-sm text-slate-500">{{ $catalogue->shop->name }}</p>
                        <p class="mt-4 text-sm font-semibold text-slate-700">{{ $catalogue->valid_from->format('d M') }} - {{ $catalogue->valid_to->format('d M Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="panel p-6 text-sm text-slate-500 sm:col-span-2 lg:col-span-4">No active catalogues are available.</div>
            @endforelse
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold text-slate-950">Featured shops</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($shops as $shop)
                    <a href="{{ route('shops.show', $shop->slug) }}" class="panel p-5 transition hover:shadow-md">
                        <p class="font-bold text-slate-950">{{ $shop->name }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $shop->category?->name ?? 'Retailer' }}</p>
                        <p class="mt-4 text-sm text-slate-600">{{ $shop->phone ?? $shop->whatsapp ?? $shop->email }}</p>
                    </a>
                @empty
                    <div class="panel p-6 text-sm text-slate-500">No active shops yet.</div>
                @endforelse
            </div>
        </div>
    </section>
</x-app-layout>
