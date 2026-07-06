<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-950">Shop profile</h1>
        <form method="POST" action="{{ route('shop.profile.update') }}" class="panel mt-6 space-y-5 p-6">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="label">Shop name</label>
                    <input name="name" value="{{ old('name', $shop->name) }}" class="field mt-1" required>
                </div>
                <div>
                    <label class="label">Trading name</label>
                    <input name="trading_name" value="{{ old('trading_name', $shop->trading_name) }}" class="field mt-1">
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $shop->email) }}" class="field mt-1">
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input name="phone" value="{{ old('phone', $shop->phone) }}" class="field mt-1">
                </div>
                <div>
                    <label class="label">WhatsApp</label>
                    <input name="whatsapp" value="{{ old('whatsapp', $shop->whatsapp) }}" class="field mt-1">
                </div>
                <div>
                    <label class="label">Website</label>
                    <input type="url" name="website_url" value="{{ old('website_url', $shop->website_url) }}" class="field mt-1">
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Facebook page</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $shop->facebook_url) }}" class="field mt-1">
                </div>
            </div>
            <div>
                <label class="label">Description</label>
                <textarea name="description" rows="5" class="field mt-1">{{ old('description', $shop->description) }}</textarea>
            </div>
            @if(auth()->user()->isSuperAdmin())
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="field mt-1">
                        @foreach(['pending', 'active', 'suspended', 'rejected', 'archived'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $shop->status) === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <button class="btn-primary">Save profile</button>
        </form>
    </div>
</x-app-layout>
