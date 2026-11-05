<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Dashboard — MR. COFFEE</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="dashboard-body">
<header class="dashboard-header">
    <div class="brand brand-dark"><span class="brand-mark" aria-hidden="true">☕</span><span><strong>MR. COFFEE</strong><small>POS &amp; ORDER DESK</small></span></div>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout" type="submit">Keluar <span aria-hidden="true">↗</span></button></form>
</header>
<main class="dashboard-main">
    <span class="eyebrow"><span class="dot"></span> AKUN AKTIF</span>
    <p class="overline">DASHBOARD</p>
    <h1>Selamat datang,<br><span>{{ auth()->user()->name }}.</span></h1>
    <p>Anda berhasil masuk ke sistem MR. COFFEE.</p>
    <section class="placeholder-card" aria-labelledby="next-title"><div class="placeholder-icon" aria-hidden="true">✦</div><div><h2 id="next-title">Dashboard dalam pengembangan</h2><p>Halaman ini adalah tempat sementara setelah login. Fitur operasional akan tersedia setelah modul tim terintegrasi.</p></div></section>
</main>
</body>
</html>
