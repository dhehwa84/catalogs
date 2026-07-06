<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="panel p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-950">{{ $shop->name }}</h1>
                    <p class="mt-2 text-slate-500">Status: {{ ucfirst($shop->status) }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('admin.shops.approve', $shop) }}">@csrf<button class="btn-primary">Approve</button></form>
                    <form method="POST" action="{{ route('admin.shops.reject', $shop) }}">@csrf<button class="btn-secondary">Reject</button></form>
                    <form method="POST" action="{{ route('admin.shops.suspend', $shop) }}">@csrf<button class="btn-danger">Suspend</button></form>
                </div>
            </div>
            <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
                <div><dt class="font-semibold text-slate-500">Email</dt><dd>{{ $shop->email ?: 'Not provided' }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Phone</dt><dd>{{ $shop->phone ?: 'Not provided' }}</dd></div>
                <div><dt class="font-semibold text-slate-500">WhatsApp</dt><dd>{{ $shop->whatsapp ?: 'Not provided' }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Created</dt><dd>{{ $shop->created_at->format('d M Y') }}</dd></div>
            </dl>
            <p class="mt-6 leading-7 text-slate-600">{{ $shop->description }}</p>
        </div>
    </div>
</x-app-layout>
