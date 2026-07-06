<x-guest-layout>
    <div class="mb-6">
        <div class="flex items-center gap-3 lg:hidden">
            <x-application-logo class="h-10 w-10" />
            <div>
                <p class="text-sm font-bold text-slate-950">CatalogueHub</p>
                <p class="text-xs font-medium text-slate-500">Zimbabwe</p>
            </div>
        </div>
        <h1 class="mt-6 text-2xl font-bold text-slate-950 lg:mt-0">Create your account</h1>
        <p class="mt-2 text-sm text-slate-500">Start with a user account, then submit your shop profile for approval.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full name')" class="label" />
            <x-text-input id="name" class="field mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Tafadzwa Moyo" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email address')" class="label" />
            <x-text-input id="email" class="field mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="owner@example.co.zw" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="label" />
            <x-text-input id="password" class="field mt-1 block w-full" type="password" name="password" required autocomplete="new-password" placeholder="Create a secure password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" class="label" />
            <x-text-input id="password_confirmation" class="field mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button class="btn-primary w-full" type="submit">
            {{ __('Create account') }}
        </button>

        <div class="rounded-lg bg-slate-50 px-4 py-3 text-center text-sm text-slate-600">
            Already have access?
            <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Log in</a>
        </div>
    </form>
</x-guest-layout>
