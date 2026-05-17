<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | PT Londo Bell</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,700;0,800;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="auth-shell">
        {{-- Left panel --}}
        <div class="auth-panel">
            <div class="auth-panel-logo">
                <div class="auth-panel-logo-mark">
                    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="32" height="32" rx="9" fill="#3b7ef5" />
                        <path d="M8 22V10h5a4 4 0 010 8H8M15 10h4a5 5 0 010 10h-4V10z" stroke="white" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="auth-panel-logo-wordmark">
                    <span class="auth-panel-logo-name">Londo Bell</span>
                    <span class="auth-panel-logo-sub">Perseroan Terbatas</span>
                </div>
            </div>

            <svg class="auth-rings" viewBox="0 0 340 340" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="340" cy="340" r="80" />
                <circle cx="340" cy="340" r="140" />
                <circle cx="340" cy="340" r="200" />
                <circle cx="340" cy="340" r="260" />
            </svg>

            <div class="auth-panel-body">
                <p class="auth-panel-tag">Platform Bisnis</p>
                <h2 class="auth-panel-headline">Kelola inventori<br>dengan <em>lebih mudah</em></h2>
                <p class="auth-panel-desc">Satu platform untuk semua kebutuhan penjualan dari stok barang hingga
                    faktur pelanggan.</p>
                <ul class="auth-features">
                    <li><i class="bi bi-check-circle-fill"></i> Stok & kategori barang</li>
                    <li><i class="bi bi-check-circle-fill"></i> Faktur langsung jadi</li>
                    <li><i class="bi bi-check-circle-fill"></i> Histori transaksi lengkap</li>
                </ul>
            </div>
        </div>

        {{-- Right form --}}
        <div class="auth-form-area">
            <div class="auth-form-wrap">
                <div class="auth-form-logo">
                    <span class="auth-form-logo-prefix">PT</span>
                    <span class="auth-form-logo-name">Londo Bell</span>
                    <span class="auth-form-logo-dot"></span>
                </div>
                @yield('content')
            </div>
        </div>

    </div>
</body>

<script>
    function togglePw(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';
        icon.className = isHidden ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    function checkPw(val) {
        const rules = {
            'rule-min': val.length >= 6,
            'rule-max': val.length <= 12 && val.length > 0,
            'rule-num': /\d/.test(val),
            'rule-upper': /[A-Z]/.test(val),
        };

        for (const [id, pass] of Object.entries(rules)) {
            const li = document.getElementById(id);
            if (!li) continue;
            li.className = pass ? 'pass' : '';
            li.querySelector('i').className = pass
                ? 'bi bi-check-circle-fill'
                : 'bi bi-x-circle-fill';
        }
    }
</script>

</html>