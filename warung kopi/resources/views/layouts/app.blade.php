<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MR. COFFEE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 font-sans text-stone-900 antialiased dark:bg-stone-950 dark:text-stone-100">
<header class="border-b border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
    <nav aria-label="Navigasi utama" class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-8">
        <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-tight text-stone-900 dark:text-white">☕ MR. COFFEE</a>
        <div class="flex flex-wrap items-center gap-4 text-sm font-semibold">
            <a href="{{ route('dashboard') }}" class="rounded py-3 text-stone-600 hover:text-stone-900 focus-visible:outline-2 focus-visible:outline-offset-4 dark:text-stone-300 dark:hover:text-white">Dashboard</a>
            @if (auth()->user()->role === 'kasir')
                <a href="{{ route('order.form') }}" @if (request()->routeIs('order.form')) aria-current="page" @endif class="rounded py-3 text-amber-800 focus-visible:outline-2 focus-visible:outline-offset-4 dark:text-amber-300">Buat pesanan</a>
                <a href="{{ route('menu.manage') }}" @if (request()->routeIs('menu.manage')) aria-current="page" @endif class="rounded py-3 text-amber-800 focus-visible:outline-2 focus-visible:outline-offset-4 dark:text-amber-300">Kelola menu</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="min-h-11 rounded-lg border border-stone-300 px-4 hover:bg-stone-100 focus-visible:outline-2 focus-visible:outline-offset-2 dark:border-stone-600 dark:hover:bg-stone-800">Keluar</button></form>
        </div>
    </nav>
</header>
<main class="mx-auto max-w-6xl px-4 py-8 sm:px-8 sm:py-12">@yield('content')</main>
@stack('scripts')
</body>
</html>
