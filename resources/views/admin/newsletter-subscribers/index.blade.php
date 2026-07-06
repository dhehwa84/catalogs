<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-950">Newsletter subscribers</h1>
                <p class="mt-2 text-sm text-slate-600">Visitors who asked to receive specials by email.</p>
            </div>
            <div class="text-sm font-semibold text-slate-600">{{ $subscribers->total() }} total</div>
        </div>

        <div class="mt-6 overflow-hidden panel">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left font-semibold text-slate-600">
                    <tr>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Subscribed</th>
                        <th class="px-5 py-3">IP address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subscribers as $subscriber)
                        <tr>
                            <td class="px-5 py-4 font-semibold text-slate-950">{{ $subscriber->email }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $subscriber->subscribed_at->format('M j, Y H:i') }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $subscriber->ip_address ?? 'Unknown' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-slate-500">No subscribers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $subscribers->links() }}</div>
    </div>
</x-app-layout>
