<x-app-layout>
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <span class="badge bg-emerald-50 text-emerald-700">{{ $shop->category?->name ?? 'Retailer' }}</span>
            <h1 class="mt-4 text-4xl font-bold text-slate-950">{{ $shop->name }}</h1>
            <p class="mt-3 max-w-3xl leading-7 text-slate-600">{{ $shop->description ?: 'Zimbabwean retailer publishing current catalogues and promotions.' }}</p>
            <div class="mt-6 flex flex-wrap gap-3 text-sm text-slate-600">
                @if($shop->phone)<span>{{ $shop->phone }}</span>@endif
                @if($shop->whatsapp)<span>WhatsApp {{ $shop->whatsapp }}</span>@endif
                @if($shop->email)<span>{{ $shop->email }}</span>@endif
            </div>
        </div>
    </div>

    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_360px] lg:px-8">
        <section>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-950">Active catalogues</h2>
                <a href="{{ route('catalogues.index', ['shop_id' => $shop->id]) }}" class="btn-secondary">Filter this shop</a>
            </div>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                @forelse($catalogues as $catalogue)
                    <a href="{{ route('catalogues.show', [$shop->slug, $catalogue->slug]) }}" class="panel overflow-hidden transition hover:shadow-md">
                        <div class="aspect-[16/10] bg-slate-100">
                            @if($catalogue->cover_image)
                                <img src="{{ asset('storage/'.$catalogue->cover_image) }}" alt="{{ $catalogue->title }}" class="h-full w-full object-cover">
                            @endif
                        </div>
                        <div class="p-5">
                            <span class="badge bg-emerald-50 text-emerald-700">{{ $catalogue->category?->name ?? 'Promotion' }}</span>
                            <h3 class="mt-3 font-bold text-slate-950">{{ $catalogue->title }}</h3>
                            <p class="mt-3 text-sm font-semibold text-slate-700">Valid until {{ $catalogue->valid_to->format('d M Y') }}</p>
                        </div>
                    </a>
                @empty
                    <div class="panel p-6 text-sm text-slate-500 sm:col-span-2">This shop has no active catalogues right now.</div>
                @endforelse
            </div>
        </section>

        <aside>
            <h2 class="text-2xl font-bold text-slate-950">Branches</h2>
            <div class="mt-5 space-y-4">
                @forelse($shop->branches as $branch)
                    <div class="panel p-5">
                        <p class="font-bold text-slate-950">{{ $branch->name }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $branch->address ?: trim(($branch->suburb?->name ? $branch->suburb->name.', ' : '').($branch->city?->name ?? ''), ', ') }}</p>
                        <div class="mt-3 space-y-1 text-sm text-slate-600">
                            @if($branch->phone)<p>{{ $branch->phone }}</p>@endif
                            @if($branch->email)<p>{{ $branch->email }}</p>@endif
                        </div>
                        @php
                            $directions = $branch->latitude && $branch->longitude
                                ? 'https://www.google.com/maps/dir/?api=1&destination='.$branch->latitude.','.$branch->longitude
                                : 'https://www.google.com/maps/search/?api=1&query='.urlencode($branch->address ?: $branch->name.' '.$shop->name.' Zimbabwe');
                        @endphp
                        <a href="{{ $directions }}" target="_blank" class="mt-4 inline-flex text-sm font-bold text-emerald-700">Get directions</a>
                        @include('partials.branch-operating-hours', ['branch' => $branch])
                    </div>
                @empty
                    <div class="panel p-5 text-sm text-slate-500">No branches have been added.</div>
                @endforelse
            </div>
        </aside>
    </div>
</x-app-layout>
