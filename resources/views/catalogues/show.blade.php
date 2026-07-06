<x-app-layout>
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_360px] lg:px-8">
        <article class="panel overflow-hidden">
            <div class="aspect-[16/9] bg-slate-100">
                @if($catalogue->cover_image)
                    <img src="{{ asset('storage/'.$catalogue->cover_image) }}" alt="{{ $catalogue->title }}" class="h-full w-full object-cover">
                @endif
            </div>
            <div class="p-6">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge bg-emerald-50 text-emerald-700">{{ $catalogue->category?->name ?? 'Promotion' }}</span>
                    @if($catalogue->valid_to->lt(today()))
                        <span class="badge bg-rose-50 text-rose-700">Expired</span>
                    @else
                        <span class="badge bg-slate-100 text-slate-700">Active</span>
                    @endif
                </div>
                <h1 class="mt-4 text-3xl font-bold text-slate-950">{{ $catalogue->title }}</h1>
                <p class="mt-3 leading-7 text-slate-600">{{ $catalogue->description ?: 'No extra description was provided for this catalogue.' }}</p>
                <div class="mt-5 grid gap-3 border-y border-slate-200 py-5 text-sm sm:grid-cols-3">
                    <div>
                        <p class="font-semibold text-slate-500">Shop</p>
                        <a href="{{ route('shops.show', $shop->slug) }}" class="mt-1 inline-block font-bold text-emerald-700">{{ $shop->name }}</a>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-500">Starts</p>
                        <p class="mt-1 font-bold text-slate-950">{{ $catalogue->valid_from->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-500">Ends</p>
                        <p class="mt-1 font-bold text-slate-950">{{ $catalogue->valid_to->format('d M Y') }}</p>
                    </div>
                </div>
                @if($catalogue->file_path)
                    <a href="{{ asset('storage/'.$catalogue->file_path) }}" target="_blank" class="btn-primary mt-6">Open catalogue file</a>
                @endif
            </div>
        </article>

        <aside class="space-y-6">
            <section class="panel p-5">
                <h2 class="text-lg font-bold text-slate-950">Store contact</h2>
                <div class="mt-4 space-y-2 text-sm text-slate-600">
                    @if($shop->phone)<p><span class="font-semibold text-slate-800">Phone:</span> {{ $shop->phone }}</p>@endif
                    @if($shop->whatsapp)<p><span class="font-semibold text-slate-800">WhatsApp:</span> {{ $shop->whatsapp }}</p>@endif
                    @if($shop->email)<p><span class="font-semibold text-slate-800">Email:</span> {{ $shop->email }}</p>@endif
                    @if($shop->website_url)<p><a href="{{ $shop->website_url }}" target="_blank" class="font-semibold text-emerald-700">Website</a></p>@endif
                </div>
            </section>

            <section class="panel p-5">
                <h2 class="text-lg font-bold text-slate-950">Branch coverage</h2>
                <div class="mt-4 space-y-4">
                    @forelse($catalogue->branches as $branch)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <p class="font-bold text-slate-950">{{ $branch->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $branch->address ?: trim(($branch->suburb?->name ? $branch->suburb->name.', ' : '').($branch->city?->name ?? ''), ', ') }}</p>
                            @php
                                $directions = $branch->latitude && $branch->longitude
                                    ? 'https://www.google.com/maps/dir/?api=1&destination='.$branch->latitude.','.$branch->longitude
                                    : 'https://www.google.com/maps/search/?api=1&query='.urlencode($branch->address ?: $branch->name.' '.$shop->name.' Zimbabwe');
                            @endphp
                            <a href="{{ $directions }}" target="_blank" class="mt-3 inline-flex text-sm font-bold text-emerald-700">Get directions</a>
                            @include('partials.branch-operating-hours', ['branch' => $branch])
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">This catalogue has not been linked to branches yet.</p>
                    @endforelse
                </div>
            </section>

            @if($catalogue->areas->isNotEmpty())
                <section class="panel p-5">
                    <h2 class="text-lg font-bold text-slate-950">Promo areas</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($catalogue->areas as $area)
                            <span class="badge bg-slate-100 text-slate-700">{{ $area->name }}</span>
                        @endforeach
                    </div>
                </section>
            @endif
        </aside>
    </div>
</x-app-layout>
