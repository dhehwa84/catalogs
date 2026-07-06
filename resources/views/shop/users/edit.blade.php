<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-slate-950">Edit shop user</h1>
            @unless($user->pivot?->is_owner)
                <form method="POST" action="{{ route('shop.users.destroy', $user) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn-danger">Remove</button>
                </form>
            @endunless
        </div>
        <form method="POST" action="{{ route('shop.users.update', $user) }}" class="panel mt-6 space-y-5 p-6">
            @csrf
            @method('PUT')
            @include('shop.users._form')
            <button class="btn-primary">Save user</button>
        </form>
    </div>
</x-app-layout>
