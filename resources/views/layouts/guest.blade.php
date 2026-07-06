<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CatalogueHub Zimbabwe') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <main class="min-h-screen bg-slate-50">
            <div class="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-[1fr_480px]">
                <section class="hidden border-r border-slate-200 bg-white px-10 py-10 lg:flex lg:flex-col">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <x-application-logo class="h-11 w-11" />
                        <span class="leading-tight">
                            <span class="block text-base font-bold text-slate-950">CatalogueHub</span>
                            <span class="block text-xs font-medium text-slate-500">Zimbabwe</span>
                        </span>
                    </a>

                    <div class="flex flex-1 flex-col justify-center">
                        <p class="text-sm font-bold uppercase tracking-wide text-emerald-700">Central catalogue platform</p>
                        <h1 class="mt-4 max-w-xl text-4xl font-bold tracking-tight text-slate-950">Manage promotions and branch coverage from one retail workspace.</h1>
                        <p class="mt-5 max-w-lg leading-7 text-slate-600">Shops upload catalogues, set validity dates, assign covered areas, and publish branch details for customers across Zimbabwe.</p>

                        <div class="mt-8 grid max-w-lg gap-3">
                            <div class="rounded-lg border border-slate-200 p-4">
                                <p class="font-semibold text-slate-950">Public browsing</p>
                                <p class="mt-1 text-sm text-slate-500">Customers filter current catalogues by shop, city, area, and date.</p>
                            </div>
                            <div class="rounded-lg border border-slate-200 p-4">
                                <p class="font-semibold text-slate-950">Shop portal</p>
                                <p class="mt-1 text-sm text-slate-500">Retail teams manage catalogues, branches, users, hours, and contact details.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="flex min-h-screen flex-col px-4 py-6 sm:px-6 lg:px-10">
                    <div class="flex items-center justify-between lg:hidden">
                        <a href="{{ route('home') }}" class="flex items-center gap-3">
                            <x-application-logo class="h-10 w-10" />
                            <span class="leading-tight">
                                <span class="block text-sm font-bold text-slate-950">CatalogueHub</span>
                                <span class="block text-xs font-medium text-slate-500">Zimbabwe</span>
                            </span>
                        </a>
                    </div>

                    <div class="flex flex-1 items-center justify-center py-8">
                        <div class="w-full max-w-md">
                            <div class="panel p-6 sm:p-8">
                                {{ $slot }}
                            </div>
                            <p class="mt-6 text-center text-xs text-slate-500">
                                CatalogueHub Zimbabwe keeps expired catalogues hidden by default unless shoppers filter for them.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
