const app = document.getElementById('menu-app');

if (app) {
    const api = app.dataset.apiUrl.replace(/\/$/, '');
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const list = document.getElementById('menu-list');
    const form = document.getElementById('menu-form');
    const notice = document.getElementById('menu-notice');
    const loading = document.getElementById('menu-loading');
    const empty = document.getElementById('menu-empty');
    const count = document.getElementById('menu-count');
    const save = document.getElementById('menu-save');
    const cancel = document.getElementById('menu-cancel');
    const title = document.getElementById('form-title');
    const fields = ['name', 'category_id', 'price'];
    let editingId = null;
    let busy = false;
    let menus = [];

    function showNotice(message, error = false) {
        notice.textContent = message;
        notice.className = error
            ? 'rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-900 dark:border-red-800 dark:bg-red-950 dark:text-red-100'
            : 'rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-900 dark:border-green-800 dark:bg-green-950 dark:text-green-100';
        notice.setAttribute('role', error ? 'alert' : 'status');
    }

    function clearErrors() {
        fields.forEach((field) => {
            document.getElementById(`error-${field}`).textContent = '';
            form.elements[field].removeAttribute('aria-invalid');
        });
    }

    function resetForm() {
        form.reset();
        clearErrors();
        editingId = null;
        title.textContent = 'Tambah menu';
        save.textContent = 'Simpan menu';
        cancel.classList.add('hidden');
    }

    async function request(url, options = {}) {
        let response;
        try {
            response = await fetch(url, {
                credentials: 'same-origin',
                ...options,
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, ...options.headers },
            });
        } catch {
            throw new Error('Tidak dapat menghubungi server. Periksa koneksi lalu coba lagi.');
        }
        if (response.redirected || !response.headers.get('content-type')?.includes('application/json')) {
            throw new Error('Sesi mungkin telah berakhir. Muat ulang halaman dan masuk kembali.');
        }
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            const error = new Error(response.status === 419 ? 'Sesi berakhir. Muat ulang halaman dan masuk kembali.' : response.status === 401 ? 'Sesi berakhir. Silakan masuk kembali.' : response.status === 403 ? 'Anda tidak memiliki akses untuk mengelola menu.' : data.message || 'Permintaan gagal. Coba lagi.');
            error.errors = data.errors || {};
            throw error;
        }
        return data;
    }

    function setBusy(value) {
        busy = value;
        save.disabled = value || form.elements.category_id.options.length <= 1;
        cancel.disabled = value;
        document.getElementById('menu-reload').disabled = value;
        list.querySelectorAll('button').forEach((button) => { button.disabled = value; });
    }

    function actionButton(label, style, handler) {
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = label;
        button.className = style;
        button.addEventListener('click', handler);
        return button;
    }

    function renderMenus() {
        list.replaceChildren();
        empty.classList.toggle('hidden', menus.length !== 0);
        count.textContent = `${menus.length} menu`;
        menus.forEach((menu) => {
            const item = document.createElement('li');
            item.className = 'flex flex-wrap items-center justify-between gap-4 px-5 py-4';
            const info = document.createElement('div');
            info.className = 'min-w-0 max-w-full break-words';
            const name = document.createElement('h3');
            name.className = 'font-semibold';
            name.textContent = menu.name;
            const detail = document.createElement('p');
            detail.className = 'mt-1 text-sm text-stone-600 dark:text-stone-400';
            detail.textContent = `${menu.category_name} · ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 2 }).format(Number(menu.price))}${menu.is_available ? '' : ' · Tidak tersedia'}`;
            info.append(name, detail);
            const actions = document.createElement('div');
            actions.className = 'flex gap-2';
            const buttonStyle = 'min-h-11 rounded-lg border border-stone-300 px-4 text-sm font-semibold hover:bg-stone-100 focus-visible:outline-2 dark:border-stone-600 dark:hover:bg-stone-800 disabled:opacity-50';
            const edit = actionButton('Ubah', buttonStyle, () => {
                if (busy) return;
                editingId = menu.id;
                clearErrors();
                form.elements.name.value = menu.name;
                form.elements.category_id.value = String(menu.category_id);
                form.elements.price.value = menu.price;
                title.textContent = 'Ubah menu';
                save.textContent = 'Simpan perubahan';
                cancel.classList.remove('hidden');
                form.elements.name.focus();
            });
            edit.setAttribute('aria-label', `Ubah ${menu.name}`);
            const remove = actionButton('Hapus', buttonStyle + ' text-red-700 dark:text-red-300', async () => {
                if (busy || !window.confirm(`Hapus menu “${menu.name}”? Menu akan disembunyikan dari daftar.`)) return;
                setBusy(true);
                try {
                    const result = await request(`${api}/${encodeURIComponent(menu.id)}`, { method: 'DELETE' });
                    if (editingId === menu.id) resetForm();
                    showNotice(result.message || 'Menu berhasil dihapus.');
                    await loadMenus();
                } catch (error) { showNotice(error.message || 'Tidak dapat menghubungi server.', true); }
                finally { setBusy(false); }
            });
            remove.setAttribute('aria-label', `Hapus ${menu.name}`);
            actions.append(edit, remove);
            item.append(info, actions);
            list.append(item);
        });
    }

    async function loadMenus() {
        loading.classList.remove('hidden');
        empty.classList.add('hidden');
        try {
            const result = await request(api);
            if (!Array.isArray(result.data)) throw new Error('Data menu tidak dapat dibaca. Coba muat ulang.');
            menus = result.data;
            renderMenus();
        } catch (error) {
            count.textContent = 'Data belum tersedia';
            list.replaceChildren();
            showNotice(error.message || 'Tidak dapat menghubungi server.', true);
        } finally { loading.classList.add('hidden'); }
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (busy) return;
        clearErrors();
        const payload = Object.fromEntries(fields.map((field) => [field, form.elements[field].value.trim()]));
        setBusy(true);
        try {
            const result = await request(editingId === null ? api : `${api}/${encodeURIComponent(editingId)}`, {
                method: editingId === null ? 'POST' : 'PUT', body: JSON.stringify(payload),
            });
            resetForm();
            showNotice(result.message || 'Menu berhasil disimpan.');
            await loadMenus();
        } catch (error) {
            let firstInvalid = null;
            fields.forEach((field) => {
                if (error.errors?.[field]) {
                    document.getElementById(`error-${field}`).textContent = error.errors[field].join(' ');
                    form.elements[field].setAttribute('aria-invalid', 'true');
                    firstInvalid ||= form.elements[field];
                }
            });
            showNotice(error.message || 'Tidak dapat menghubungi server.', true);
            firstInvalid?.focus();
        } finally { setBusy(false); }
    });
    cancel.addEventListener('click', resetForm);
    document.getElementById('menu-reload').addEventListener('click', async () => {
        if (busy) return;
        setBusy(true);
        notice.classList.add('hidden');
        await loadMenus();
        setBusy(false);
    });
    loadMenus();
}
