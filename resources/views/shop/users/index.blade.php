<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-950">Shop users</h1>
                <p class="mt-1 text-slate-500">{{ $shop->name }}</p>
            </div>
            <a href="{{ route('shop.users.create') }}" class="btn-primary">Add user</a>
        </div>
        <div class="mt-6 overflow-hidden panel">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left font-semibold text-slate-600">
                    <tr><th class="px-5 py-3">Name</th><th class="px-5 py-3">Email</th><th class="px-5 py-3">Role</th><th class="px-5 py-3"></th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-5 py-4 font-semibold">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ str_replace('_', ' ', $user->pivot->role) }}</td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('shop.users.edit', $user) }}" class="font-semibold text-emerald-700">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
