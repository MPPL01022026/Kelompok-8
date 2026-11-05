<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk — MR. COFFEE</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
<main class="auth-shell">
    <section class="story-panel" aria-label="Tentang sistem MR. COFFEE">
        <div class="brand brand-light">
            <span class="brand-mark" aria-hidden="true">☕</span>
            <span><strong>MR. COFFEE</strong><small>POS &amp; ORDER DESK</small></span>
        </div>
        <div class="coffee-art" aria-hidden="true"><div class="steam">﹏</div><div class="cup"><span></span></div><div class="saucer"></div></div>
        <div class="story-copy">
            <span class="eyebrow eyebrow-light"><span class="dot"></span> SISTEM INTERNAL</span>
            <h1>Pesanan tertata,<br>pelayanan lebih bermakna.</h1>
            <p>Kelola pesanan warung kopi dengan lebih rapi, dari meja hingga kasir.</p>
        </div>
        <p class="panel-foot">Dibuat untuk operasional tim MR. COFFEE.</p>
    </section>
    <section class="form-panel" aria-labelledby="login-title">
        <div class="form-top"><span class="eyebrow"><span aria-hidden="true">◈</span> PORTAL KARYAWAN</span><span class="secure-label">AKSES TERBATAS</span></div>
        <div class="form-heading">
            <p class="overline">SELAMAT DATANG KEMBALI</p>
            <h2 id="login-title">Masuk ke sistem<span>.</span></h2>
            <p>Gunakan akun yang diberikan pengelola untuk melanjutkan.</p>
        </div>
        @if (session('status'))
            <div class="notice notice-success" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="notice notice-error" role="alert">
                <strong>Belum berhasil masuk.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="field">
                <label for="username">Username <span>ID KARYAWAN</span></label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" maxlength="50" placeholder="Masukkan username" required autofocus @error('username') aria-invalid="true" @enderror>
            </div>
            <div class="field">
                <label for="password">Kata sandi</label>
                <div class="password-wrap">
                    <input id="password" name="password" type="password" autocomplete="current-password" maxlength="255" placeholder="Masukkan kata sandi" required @error('password') aria-invalid="true" @enderror>
                    <button class="reveal" type="button" id="toggle-password" aria-controls="password" aria-pressed="false">Tampilkan</button>
                </div>
            </div>
            <button class="submit" type="submit">Masuk ke sistem <span aria-hidden="true">→</span></button>
        </form>
        <div class="access-note"><span class="note-icon" aria-hidden="true">i</span><p><strong>Akses khusus karyawan.</strong> Akun disediakan oleh pengelola. Hubungi pengelola jika mengalami kendala masuk.</p></div>
        <div class="form-footer"><span>MR. COFFEE · SISTEM PESANAN</span><span>Dibuat untuk tim</span></div>
    </section>
</main>
<script>
    const toggle = document.getElementById('toggle-password');
    const password = document.getElementById('password');
    toggle.addEventListener('click', () => {
        const showing = password.type === 'password';
        password.type = showing ? 'text' : 'password';
        toggle.textContent = showing ? 'Sembunyikan' : 'Tampilkan';
        toggle.setAttribute('aria-pressed', String(showing));
    });
</script>
</body>
</html>
