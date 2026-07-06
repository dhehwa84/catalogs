<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-950">Branches</h1>
                <p class="mt-1 text-slate-500">{{ $shop->name }}</p>
            </div>
            <a href="{{ route('shop.branches.create') }}" class="btn-primary">Add branch</a>
        </div>
        <div class="mt-6 overflow-hidden panel">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left font-semibold text-slate-600">
                    <tr><th class="px-5 py-3">Branch</th><th class="px-5 py-3">Location</th><th class="px-5 py-3">Contact</th><th class="px-5 py-3"></th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($branches as $branch)
                        <tr>
                            <td class="px-5 py-4 font-semibold">{{ $branch->name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $branch->city?->name }} {{ $branch->suburb?->name ? '· '.$branch->suburb->name : '' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $branch->phone ?? $branch->email }}</td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('shop.branches.edit', $branch) }}" class="font-semibold text-emerald-700">Edit</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-500">No branches yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
