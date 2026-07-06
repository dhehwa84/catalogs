<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-950">Add shop user</h1>
        <form method="POST" action="{{ route('shop.users.store') }}" class="panel mt-6 space-y-5 p-6">
            @csrf
            @include('shop.users._form')
            <button class="btn-primary">Create user</button>
        </form>
    </div>
</x-app-layout>
