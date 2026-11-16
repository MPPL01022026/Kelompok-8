@extends('layouts.app')

@section('title', 'Buat pesanan — MR. COFFEE')

@section('content')
<div id="order-app" data-create-url="{{ route('orders.create') }}" data-store-url="{{ route('orders.store') }}" class="space-y-8">
    <div>
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-800 dark:text-amber-300">Operasional / Pesanan</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Buat pesanan</h1>
        <p class="mt-3 max-w-2xl text-base leading-6 text-stone-600 dark:text-stone-300">Catat pesanan pelanggan, pilih menu dan jumlah, lalu simpan.</p>
    </div>
    <noscript><p role="alert" class="rounded-lg border border-red-300 bg-red-50 p-4 text-red-900">Aktifkan JavaScript untuk mencatat pesanan.</p></noscript>
    <div id="order-notice" role="status" aria-live="polite" class="hidden rounded-lg border p-4 text-sm"></div>
    <section id="order-success" role="status" aria-live="polite" aria-labelledby="success-title" class="hidden rounded-xl border border-green-300 bg-green-50 p-5 text-green-950 dark:border-green-800 dark:bg-green-950 dark:text-green-100">
        <h2 id="success-title" class="text-xl font-bold">Pesanan berhasil dicatat</h2>
        <p id="success-detail" class="mt-2 break-words"></p>
    </section>
    <form id="order-form" method="POST" action="{{ route('orders.store') }}" novalidate>
        @csrf
        <fieldset id="order-fields" disabled class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">
            <section aria-labelledby="items-title" class="min-w-0 rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
                <div class="border-b border-stone-200 px-5 py-5 dark:border-stone-800"><h2 id="items-title" class="text-xl font-bold">Item pesanan</h2><p class="mt-1 text-sm text-stone-600 dark:text-stone-400">Pilih menu dan tentukan jumlah setiap item.</p></div>
                <div class="space-y-4 p-5">
                    <p id="order-loading" class="text-stone-600 dark:text-stone-300">Memuat menu dan meja…</p>
                    <p id="order-empty-menus" class="hidden text-stone-600 dark:text-stone-300">Belum ada menu tersedia. Tambahkan menu terlebih dahulu di halaman Kelola menu.</p>
                    <p id="error-items" class="text-sm text-red-700 dark:text-red-300" aria-live="polite"></p>
                    <div id="order-items" class="space-y-4"></div>
                    <button id="add-item" type="button" class="min-h-11 rounded-lg border border-stone-300 px-4 text-sm font-semibold hover:bg-stone-100 focus-visible:outline-2 dark:border-stone-600 dark:hover:bg-stone-800 disabled:opacity-50">+ Tambah item</button>
                </div>
            </section>
            <section aria-labelledby="details-title" class="rounded-xl border border-stone-200 bg-white p-5 dark:border-stone-800 dark:bg-stone-900">
                <h2 id="details-title" class="text-xl font-bold">Detail pesanan</h2>
                <div class="mt-5 space-y-4">
                    <div><label for="order-type" class="block text-sm font-semibold">Jenis pesanan</label><select id="order-type" name="order_type" aria-describedby="error-order_type" class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-base text-stone-900 focus-visible:outline-2 focus-visible:outline-amber-800 dark:border-stone-600 dark:bg-stone-950 dark:text-white"><option value="dine_in">Makan di tempat</option><option value="take_away">Bawa pulang</option></select><p id="error-order_type" class="mt-1 text-sm text-red-700 dark:text-red-300"></p></div>
                    <div id="table-field"><label for="order-table" class="block text-sm font-semibold">Nomor meja <span class="font-normal text-stone-600 dark:text-stone-400">(opsional)</span></label><select id="order-table" name="dining_table_id" aria-describedby="error-dining_table_id table-help" class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-base text-stone-900 focus-visible:outline-2 focus-visible:outline-amber-800 dark:border-stone-600 dark:bg-stone-950 dark:text-white"><option value="">Tanpa meja</option></select><p id="table-help" class="mt-1 text-sm text-stone-600 dark:text-stone-400">Pilih meja yang tersedia, jika diperlukan.</p><p id="error-dining_table_id" class="mt-1 text-sm text-red-700 dark:text-red-300"></p></div>
                    <div><label for="order-notes" class="block text-sm font-semibold">Catatan <span class="font-normal text-stone-600 dark:text-stone-400">(opsional)</span></label><textarea id="order-notes" name="notes" rows="3" maxlength="255" aria-describedby="error-notes" class="mt-2 w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-base text-stone-900 focus-visible:outline-2 focus-visible:outline-amber-800 dark:border-stone-600 dark:bg-stone-950 dark:text-white"></textarea><p id="error-notes" class="mt-1 text-sm text-red-700 dark:text-red-300"></p></div>
                    <div class="border-t border-stone-200 pt-4 dark:border-stone-800"><div class="flex items-baseline justify-between gap-4"><span class="font-semibold">Perkiraan total</span><strong id="order-total" class="text-lg">Rp 0</strong></div><p class="mt-2 text-sm text-stone-600 dark:text-stone-400">Harga dan total akhir ditentukan server saat pesanan disimpan.</p></div>
                    <button id="order-submit" type="submit" class="min-h-11 w-full rounded-lg bg-amber-900 px-5 text-sm font-semibold text-white hover:bg-amber-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-amber-700 dark:hover:bg-amber-600">Simpan pesanan</button>
                </div>
            </section>
        </fieldset>
    </form>
    <button id="order-retry-load" type="button" class="hidden min-h-11 rounded-lg border border-stone-300 bg-white px-4 text-sm font-semibold hover:bg-stone-100 focus-visible:outline-2 dark:border-stone-600 dark:bg-stone-900 dark:hover:bg-stone-800">Muat ulang data</button>
    <button id="order-new" type="button" class="hidden min-h-11 rounded-lg border border-stone-300 bg-white px-4 text-sm font-semibold hover:bg-stone-100 focus-visible:outline-2 dark:border-stone-600 dark:bg-stone-900 dark:hover:bg-stone-800">Buat pesanan baru</button>
    <template id="order-item-template">
        <div class="order-item rounded-lg border border-stone-200 p-4 dark:border-stone-700">
            <div class="flex flex-wrap items-center justify-between gap-2"><h3 class="item-heading font-semibold"></h3><button type="button" class="item-remove min-h-11 rounded-lg px-3 text-sm font-semibold text-red-700 hover:bg-red-50 focus-visible:outline-2 dark:text-red-300 dark:hover:bg-red-950">Hapus item</button></div>
            <div class="mt-3 grid gap-4 sm:grid-cols-[minmax(0,1fr)_130px]">
                <div><label class="menu-label block text-sm font-semibold">Menu</label><select class="item-menu mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-base text-stone-900 focus-visible:outline-2 focus-visible:outline-amber-800 dark:border-stone-600 dark:bg-stone-950 dark:text-white"><option value="">Pilih menu</option></select><p class="item-price mt-1 text-sm text-stone-600 dark:text-stone-400">Harga satuan: —</p><p class="item-menu-error mt-1 text-sm text-red-700 dark:text-red-300"></p></div>
                <div><label class="quantity-label block text-sm font-semibold">Jumlah</label><input type="number" min="1" max="65535" step="1" inputmode="numeric" value="1" class="item-quantity mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-3 text-base text-stone-900 focus-visible:outline-2 focus-visible:outline-amber-800 dark:border-stone-600 dark:bg-stone-950 dark:text-white"><p class="item-quantity-error mt-1 text-sm text-red-700 dark:text-red-300"></p></div>
            </div>
            <p class="item-subtotal mt-3 text-sm text-stone-600 dark:text-stone-400">Perkiraan subtotal: —</p>
        </div>
    </template>
</div>
@endsection
