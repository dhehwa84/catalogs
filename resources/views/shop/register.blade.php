<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-950">Register your shop</h1>
            <p class="mt-1 text-slate-500">Submit the business profile. A platform admin approves it before public listing.</p>
        </div>
        <form method="POST" action="{{ route('shop.register.store') }}" class="panel space-y-5 p-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="label">Shop name</label>
                    <input name="name" value="{{ old('name') }}" class="field mt-1" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <label class="label">Trading name</label>
                    <input name="trading_name" value="{{ old('trading_name') }}" class="field mt-1">
                </div>
                <div>
                    <label class="label">Category</label>
                    <select name="shop_category_id" class="field mt-1">
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('shop_category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="field mt-1">
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input name="phone" value="{{ old('phone') }}" class="field mt-1" placeholder="+263...">
                </div>
                <div>
                    <label class="label">WhatsApp</label>
                    <input name="whatsapp" value="{{ old('whatsapp') }}" class="field mt-1" placeholder="+263...">
                </div>
            </div>
            <div>
                <label class="label">Description</label>
                <textarea name="description" rows="4" class="field mt-1">{{ old('description') }}</textarea>
            </div>
            <button class="btn-primary">Submit shop</button>
        </form>
    </div>
</x-app-layout>
