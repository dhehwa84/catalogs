<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-950">Shops</h1>
        <div class="mt-6 overflow-hidden panel">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left font-semibold text-slate-600"><tr><th class="px-5 py-3">Shop</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Contact</th><th class="px-5 py-3"></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($shops as $shop)
                        <tr>
                            <td class="px-5 py-4 font-semibold">{{ $shop->name }}</td>
                            <td class="px-5 py-4">{{ ucfirst($shop->status) }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $shop->phone ?? $shop->email }}</td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('admin.shops.show', $shop) }}" class="font-semibold text-emerald-700">Review</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $shops->links() }}</div>
    </div>
</x-app-layout>
