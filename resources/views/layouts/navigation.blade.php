<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-emerald-700 text-sm font-bold text-white">CZ</span>
                    <span class="leading-tight">
                        <span class="block text-sm font-bold text-slate-950">CatalogueHub</span>
                        <span class="block text-xs font-medium text-slate-500">Zimbabwe</span>
                    </span>
                </a>

                <div class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('catalogues.index') }}" class="nav-item {{ request()->routeIs('catalogues.*') ? 'nav-item-active' : '' }}">Catalogues</a>
                    @auth
                        <a href="{{ route('shop.dashboard') }}" class="nav-item {{ request()->routeIs('shop.*') ? 'nav-item-active' : '' }}">Shop portal</a>
                        @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'nav-item-active' : '' }}">Admin</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-slate-600 hover:text-slate-950">{{ Auth::user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn-secondary" type="submit">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary">Register shop</a>
                @endauth
            </div>

            <button @click="open = ! open" class="grid h-10 w-10 place-items-center rounded-lg border border-slate-200 text-slate-700 md:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path :class="{'hidden': open}" d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
                    <path :class="{'hidden': ! open}" class="hidden" d="M6 18 18 6M6 6l12 12" stroke-linecap="round" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-slate-200 bg-white md:hidden">
        <div class="space-y-1 px-4 py-4">
            <a href="{{ route('home') }}" class="mobile-nav-item">Home</a>
            <a href="{{ route('catalogues.index') }}" class="mobile-nav-item">Catalogues</a>
            @auth
                <a href="{{ route('shop.dashboard') }}" class="mobile-nav-item">Shop portal</a>
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item">Admin</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="mobile-nav-item">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="mobile-nav-item w-full text-left" type="submit">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-item">Log in</a>
                <a href="{{ route('register') }}" class="mobile-nav-item">Register shop</a>
            @endauth
        </div>
    </div>
</nav>
