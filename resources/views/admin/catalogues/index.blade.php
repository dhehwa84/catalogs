<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-950">Catalogue moderation</h1>
        <div class="mt-6 overflow-hidden panel">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left font-semibold text-slate-600"><tr><th class="px-5 py-3">Catalogue</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Validity</th><th class="px-5 py-3"></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($catalogues as $catalogue)
                        <tr>
                            <td class="px-5 py-4 font-semibold">{{ $catalogue->title }}</td>
                            <td class="px-5 py-4">{{ str_replace('_', ' ', $catalogue->status) }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $catalogue->valid_from->format('d M') }} - {{ $catalogue->valid_to->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('admin.catalogues.show', $catalogue) }}" class="font-semibold text-emerald-700">Review</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $catalogues->links() }}</div>
    </div>
</x-app-layout>
