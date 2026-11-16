const app = document.getElementById('order-app');

if (app) {
    const form = document.getElementById('order-form');
    const fields = document.getElementById('order-fields');
    const items = document.getElementById('order-items');
    const template = document.getElementById('order-item-template');
    const notice = document.getElementById('order-notice');
    const add = document.getElementById('add-item');
    const submit = document.getElementById('order-submit');
    const newOrder = document.getElementById('order-new');
    const retryLoad = document.getElementById('order-retry-load');
    const type = document.getElementById('order-type');
    const table = document.getElementById('order-table');
    const total = document.getElementById('order-total');
    const success = document.getElementById('order-success');
    const token = document.querySelector('meta[name="csrf-token"]').content;
    let menus = [];
    let nextId = 0;
    let sending = false;
    let uncertain = false;

    function showNotice(message, error = false) {
        notice.textContent = message;
        notice.className = error
            ? 'rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-900 dark:border-red-800 dark:bg-red-950 dark:text-red-100'
            : 'rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-900 dark:border-green-800 dark:bg-green-950 dark:text-green-100';
        notice.setAttribute('role', error ? 'alert' : 'status');
    }

    async function request(url, options = {}) {
        let response;
        try {
            response = await fetch(url, { credentials: 'same-origin', ...options,
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, ...options.headers } });
        } catch {
            const error = new Error('Tidak dapat menghubungi server.');
            error.uncertain = true;
            throw error;
        }
        if (response.redirected || !response.headers.get('content-type')?.includes('application/json')) {
            const error = new Error('Respons server tidak dapat dipastikan.');
            error.uncertain = true;
            throw error;
        }
        let data;
        try { data = await response.json(); } catch {
            const error = new Error('Respons server tidak dapat dibaca.');
            error.uncertain = true;
            throw error;
        }
        if (!response.ok) {
            const error = new Error(response.status === 419 ? 'Sesi berakhir. Muat ulang halaman dan masuk kembali.' : response.status === 401 ? 'Sesi berakhir. Silakan masuk kembali.' : response.status === 403 ? 'Anda tidak memiliki akses untuk mencatat pesanan.' : data.message || 'Permintaan gagal.');
            error.errors = data.errors || {};
            error.uncertain = ![400, 401, 403, 419, 422].includes(response.status);
            throw error;
        }
        return data;
    }

    function priceInCents(price) {
        const match = /^(\d{1,10})(?:\.(\d{1,2}))?$/.exec(String(price));
        return match ? BigInt(match[1]) * 100n + BigInt((match[2] || '').padEnd(2, '0')) : null;
    }

    function rupiah(cents) {
        const whole = new Intl.NumberFormat('id-ID').format(cents / 100n);
        const fraction = cents % 100n;
        return `Rp ${whole}${fraction ? ',' + String(fraction).padStart(2, '0') : ''}`;
    }

    function calculate() {
        let sum = 0n;
        let complete = true;
        for (const row of items.children) {
            const menu = menus.find((entry) => String(entry.id) === row.querySelector('.item-menu').value);
            const value = row.querySelector('.item-quantity').value;
            const qty = /^\d+$/.test(value) ? Number(value) : 0;
            const price = menu ? priceInCents(menu.price) : null;
            const valid = price !== null && Number.isInteger(qty) && qty >= 1 && qty <= 65535;
            const subtotal = valid ? price * BigInt(qty) : null;
            row.querySelector('.item-price').textContent = `Harga satuan: ${price === null ? '—' : rupiah(price)}`;
            row.querySelector('.item-subtotal').textContent = `Perkiraan subtotal: ${subtotal === null ? '—' : rupiah(subtotal)}`;
            if (subtotal === null) complete = false;
            else sum += subtotal;
        }
        total.textContent = complete ? rupiah(sum) : '—';
    }

    function renumber() {
        [...items.children].forEach((row, index) => {
            row.querySelector('.item-heading').textContent = `Item ${index + 1}`;
            row.querySelector('.item-remove').setAttribute('aria-label', `Hapus item ${index + 1}`);
        });
        add.disabled = menus.length === 0 || items.children.length >= 100;
        calculate();
    }

    function addItem() {
        if (!menus.length || items.children.length >= 100) return;
        const row = template.content.firstElementChild.cloneNode(true);
        const id = ++nextId;
        for (const [field, inputClass, labelClass] of [['menu', 'item-menu', 'menu-label'], ['quantity', 'item-quantity', 'quantity-label']]) {
            const input = row.querySelector(`.${inputClass}`);
            const error = row.querySelector(`.${inputClass}-error`);
            input.id = `item-${id}-${field}`;
            error.id = `item-${id}-${field}-error`;
            input.setAttribute('aria-describedby', error.id);
            row.querySelector(`.${labelClass}`).htmlFor = input.id;
            input.addEventListener('input', calculate);
            input.addEventListener('change', calculate);
        }
        const select = row.querySelector('.item-menu');
        menus.forEach((menu) => {
            const option = document.createElement('option');
            option.value = String(menu.id);
            option.textContent = menu.name;
            select.append(option);
        });
        row.querySelector('.item-remove').addEventListener('click', () => { row.remove(); renumber(); });
        items.append(row);
        renumber();
    }

    function clearErrors() {
        document.querySelectorAll('#order-form [aria-invalid]').forEach((el) => el.removeAttribute('aria-invalid'));
        document.querySelectorAll('#order-form [id^="error-"], #order-form .item-menu-error, #order-form .item-quantity-error').forEach((el) => { el.textContent = ''; });
    }

    function displayErrors(errors) {
        let first = null;
        for (const [key, messages] of Object.entries(errors)) {
            let input, error;
            const itemKey = /^items\.(\d+)\.(menu_id|quantity)$/.exec(key);
            if (itemKey) {
                const row = items.children[Number(itemKey[1])];
                const name = itemKey[2] === 'menu_id' ? 'menu' : 'quantity';
                input = row?.querySelector(`.item-${name}`);
                error = row?.querySelector(`.item-${name}-error`);
            } else if (['order_type', 'dining_table_id', 'notes'].includes(key)) {
                input = form.elements[key];
                error = document.getElementById(`error-${key}`);
            } else if (key === 'items') error = document.getElementById('error-items');
            if (error) {
                const text = Array.isArray(messages) ? messages.join(' ') : String(messages);
                // Translate Laravel's default field-key messages; preserve custom server messages.
                const defaults = {
                    items: 'Tambahkan setidaknya satu item (maksimal 100 item).',
                    order_type: 'Pilih jenis pesanan yang tersedia.',
                    dining_table_id: 'Pilih meja yang tersedia.',
                    notes: 'Catatan maksimal 255 karakter.',
                    menu_id: 'Pilih menu yang tersedia.',
                    quantity: 'Jumlah harus berupa bilangan bulat antara 1 dan 65.535.',
                };
                const field = itemKey ? itemKey[2] : key;
                error.textContent = text.startsWith('The ') && defaults[field] ? defaults[field] : text;
            }
            if (input) { input.setAttribute('aria-invalid', 'true'); first ||= input; }
        }
        first?.focus();
    }

    async function loadData() {
        fields.disabled = true;
        retryLoad.classList.add('hidden');
        document.getElementById('order-loading').classList.remove('hidden');
        try {
            const result = await request(app.dataset.createUrl);
            if (!Array.isArray(result.data?.menus) || !Array.isArray(result.data?.dining_tables)) throw new Error('Data menu atau meja tidak dapat dibaca.');
            menus = result.data.menus;
            table.replaceChildren(new Option('Tanpa meja', ''));
            result.data.dining_tables.forEach((entry) => table.add(new Option(`Meja ${entry.number}`, String(entry.id))));
            document.getElementById('table-help').textContent = result.data.dining_tables.length ? 'Pilih meja yang tersedia, jika diperlukan.' : 'Belum ada meja aktif. Pesanan tetap dapat disimpan tanpa meja.';
            document.getElementById('order-empty-menus').classList.toggle('hidden', menus.length !== 0);
            items.replaceChildren();
            if (menus.length) addItem();
            else renumber();
            submit.disabled = menus.length === 0;
            fields.disabled = false;
        } catch (error) {
            showNotice(error.message || 'Data tidak dapat dimuat. Coba lagi.', true);
            retryLoad.classList.remove('hidden');
        } finally { document.getElementById('order-loading').classList.add('hidden'); }
    }

    add.addEventListener('click', addItem);
    type.addEventListener('change', () => {
        const takeaway = type.value === 'take_away';
        document.getElementById('table-field').classList.toggle('hidden', takeaway);
        if (takeaway) table.value = '';
    });
    retryLoad.addEventListener('click', () => { notice.classList.add('hidden'); loadData(); });
    newOrder.addEventListener('click', () => {
        if (uncertain && !window.confirm('Pastikan pesanan sebelumnya belum tersimpan sebelum membuat pesanan baru. Lanjutkan?')) return;
        uncertain = false;
        newOrder.classList.add('hidden');
        success.classList.add('hidden');
        notice.classList.add('hidden');
        form.reset();
        document.getElementById('table-field').classList.remove('hidden');
        clearErrors();
        loadData();
    });
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (sending || fields.disabled || !menus.length) return;
        clearErrors();
        notice.classList.add('hidden');
        const payload = {
            order_type: type.value,
            dining_table_id: type.value === 'take_away' ? null : (table.value || null),
            notes: form.elements.notes.value.trim() || null,
            items: [...items.children].map((row) => ({ menu_id: row.querySelector('.item-menu').value, quantity: row.querySelector('.item-quantity').value })),
        };
        sending = true;
        fields.disabled = true;
        try {
            const result = await request(app.dataset.storeUrl, { method: 'POST', body: JSON.stringify(payload) });
            if (!result.data?.order_number || result.data.total_amount == null) {
                const error = new Error('Respons penyimpanan tidak lengkap.');
                error.uncertain = true;
                throw error;
            }
            const amount = priceInCents(result.data.total_amount);
            document.getElementById('success-detail').textContent = `Nomor ${result.data.order_number} · Status: ${result.data.status} · Total akhir: ${amount === null ? result.data.total_amount : rupiah(amount)}`;
            success.classList.remove('hidden');
            success.scrollIntoView({ block: 'nearest' });
            newOrder.classList.remove('hidden');
        } catch (error) {
            if (error.uncertain) {
                uncertain = true;
                showNotice('Hasil penyimpanan belum dapat dipastikan. Periksa daftar pesanan atau tanyakan kepada tim sebelum mengirim ulang agar tidak terjadi pesanan ganda.', true);
                newOrder.classList.remove('hidden');
            } else {
                fields.disabled = false;
                showNotice(Object.keys(error.errors || {}).length ? 'Periksa kembali isian yang ditandai.' : (error.message || 'Pesanan tidak dapat disimpan. Periksa kembali isian.'), true);
                displayErrors(error.errors || {});
            }
        } finally { sending = false; }
    });
    loadData();
}
