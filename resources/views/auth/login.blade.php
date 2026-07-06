<x-guest-layout>
    <div class="mb-6">
        <div class="flex items-center gap-3 lg:hidden">
            <x-application-logo class="h-10 w-10" />
            <div>
                <p class="text-sm font-bold text-slate-950">CatalogueHub</p>
                <p class="text-xs font-medium text-slate-500">Zimbabwe</p>
            </div>
        </div>
        <h1 class="mt-6 text-2xl font-bold text-slate-950 lg:mt-0">Log in</h1>
        <p class="mt-2 text-sm text-slate-500">Access your shop portal or platform admin workspace.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email address')" class="label" />
            <x-text-input id="email" class="field mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="owner@cataloguehub.co.zw" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between gap-3">
                <x-input-label for="password" :value="__('Password')" class="label" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-emerald-700 hover:text-emerald-800" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="field mt-1 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600">
            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-700 shadow-sm focus:ring-emerald-600" name="remember">
            {{ __('Remember me') }}
        </label>

        <button class="btn-primary w-full" type="submit">
            {{ __('Log in') }}
        </button>

        <div class="rounded-lg bg-slate-50 px-4 py-3 text-center text-sm text-slate-600">
            New shop?
            <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Create an account</a>
        </div>
    </form>
</x-guest-layout>
