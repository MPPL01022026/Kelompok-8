@extends('layouts.app')

@section('title', 'Kelola menu — MR. COFFEE')

@section('content')
<div id="menu-app" data-api-url="{{ route('menus.index') }}" class="space-y-8">
    <div>
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-800 dark:text-amber-300">Operasional / Menu</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Kelola menu</h1>
        <p class="mt-3 max-w-2xl text-base leading-6 text-stone-600 dark:text-stone-300">Atur nama, kategori, dan harga menu yang tersedia untuk kasir.</p>
    </div>
    <noscript><p role="alert" class="rounded-lg border border-red-300 bg-red-50 p-4 text-red-900">Aktifkan JavaScript untuk mengelola menu.</p></noscript>
    <div id="menu-notice" role="status" aria-live="polite" class="hidden rounded-lg border p-4 text-sm"></div>
    <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">
        <section aria-labelledby="list-title" class="min-w-0 rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-200 px-5 py-5 dark:border-stone-800">
                <div><h2 id="list-title" class="text-xl font-bold">Daftar menu</h2><p id="menu-count" class="mt-1 text-sm text-stone-600 dark:text-stone-400">Memuat data…</p></div>
                <button id="menu-reload" type="button" class="min-h-11 rounded-lg border border-stone-300 px-4 text-sm font-semibold hover:bg-stone-100 focus-visible:outline-2 dark:border-stone-600 dark:hover:bg-stone-800">Muat ulang</button>
            </div>
            <div id="menu-loading" class="px-5 py-8 text-stone-600 dark:text-stone-300">Memuat menu…</div>
            <p id="menu-empty" class="hidden px-5 py-8 text-stone-600 dark:text-stone-300">Belum ada menu. Tambahkan menu melalui formulir.</p>
            <ul id="menu-list" class="divide-y divide-stone-200 dark:divide-stone-800" aria-label="Daftar menu"></ul>
        </section>
        <section aria-labelledby="form-title" class="rounded-xl border border-stone-200 bg-white p-5 dark:border-stone-800 dark:bg-stone-900">
            <h2 id="form-title" class="text-xl font-bold">Tambah menu</h2>
            <p class="mt-1 text-sm leading-6 text-stone-600 dark:text-stone-400">Isi informasi menu, lalu simpan.</p>
            @if ($categories->isEmpty())
                <p role="alert" class="mt-4 rounded-lg border border-amber-300 bg-amber-50 p-3 text-sm text-amber-950">Belum ada kategori. Minta tim backend menambahkan kategori terlebih dahulu.</p>
            @endif
            <form id="menu-form" class="mt-5 space-y-4" novalidate>
                <div><label for="menu-name" class="block text-sm font-semibold">Nama menu</label><input id="menu-name" name="name" type="text" maxlength="100" required aria-describedby="error-name" class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-base text-stone-900 focus-visible:outline-2 focus-visible:outline-amber-800 dark:border-stone-600 dark:bg-stone-950 dark:text-white"><p id="error-name" class="mt-1 text-sm text-red-700 dark:text-red-300"></p></div>
                <div><label for="menu-category" class="block text-sm font-semibold">Kategori</label><select id="menu-category" name="category_id" required aria-describedby="error-category_id" class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-base text-stone-900 focus-visible:outline-2 focus-visible:outline-amber-800 dark:border-stone-600 dark:bg-stone-950 dark:text-white"><option value="">Pilih kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select><p id="error-category_id" class="mt-1 text-sm text-red-700 dark:text-red-300"></p></div>
                <div><label for="menu-price" class="block text-sm font-semibold">Harga (Rp)</label><input id="menu-price" name="price" type="number" min="0.01" max="9999999999.99" step="0.01" inputmode="decimal" required aria-describedby="error-price" class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-base text-stone-900 focus-visible:outline-2 focus-visible:outline-amber-800 dark:border-stone-600 dark:bg-stone-950 dark:text-white"><p id="error-price" class="mt-1 text-sm text-red-700 dark:text-red-300"></p></div>
                <div class="flex flex-wrap gap-3 pt-2"><button id="menu-save" type="submit" @disabled($categories->isEmpty()) class="min-h-11 rounded-lg bg-amber-900 px-5 text-sm font-semibold text-white hover:bg-amber-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-amber-700 dark:hover:bg-amber-600">Simpan menu</button><button id="menu-cancel" type="button" class="hidden min-h-11 rounded-lg border border-stone-300 px-4 text-sm font-semibold hover:bg-stone-100 dark:border-stone-600 dark:hover:bg-stone-800">Batal ubah</button></div>
            </form>
        </section>
    </div>
</div>
@endsection
