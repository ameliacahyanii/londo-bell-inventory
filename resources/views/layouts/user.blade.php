<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | PT Londo Bell</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Sora:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    {{-- Toast Container --}}
    <div id="toast-wrap"
        style="position:fixed;top:20px;right:20px;display:flex;flex-direction:column;gap:10px;z-index:9999;pointer-events:none">
    </div>

    {{-- Modal Konfirmasi --}}
    <div id="modal-konfirmasi"
        style="display:none;position:fixed;inset:0;z-index:9998;align-items:center;justify-content:center">
        <div class="mk-backdrop" onclick="tutupKonfirmasi()"></div>
        <div class="mk-box">
            <div class="mk-icon-wrap">
                <i class="bi bi-trash3"></i>
            </div>
            <h3 class="mk-title">Hapus data ini?</h3>
            <p id="mk-pesan" class="mk-pesan">Tindakan ini tidak bisa dibatalkan.</p>
            <div class="mk-actions">
                <button onclick="tutupKonfirmasi()" class="mk-btn-batal">Batal</button>
                <button id="mk-btn-hapus" class="mk-btn-hapus">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- Topbar User --}}
    <nav class="unav">
        <div class="unav-brand">
            <div class="unav-brand-dot">
                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                    <rect width="20" height="20" rx="5" fill="#3b7ef5" />
                    <path d="M5 14V6h3.5a2.5 2.5 0 010 5H5M11 6h3a3 3 0 010 6h-3V6z" stroke="white" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" fill="none" />
                </svg>
            </div>
            <span>Londo Bell</span>
        </div>

        <div class="nav-links">
            <a href="{{ route('user.catalog') }}" class="nav-link {{ request()->routeIs('user.catalog') ? 'on' : '' }}">
                <i class="bi bi-grid"></i>
                <span class="nav-link-txt">Katalog</span>
            </a>
            <a href="{{ route('user.faktur.index') }}"
                class="nav-link {{ request()->routeIs('user.faktur.index') ? 'on' : '' }}">
                <i class="bi bi-cart3"></i>
                <span class="nav-link-txt">Keranjang</span>
                @php $cartCount = count(session('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="cart-pip">{{ $cartCount }}</span>
                @endif
            </a>
            <a href="{{ route('user.faktur.history') }}"
                class="nav-link {{ request()->routeIs('user.faktur.history') ? 'on' : '' }}">
                <i class="bi bi-receipt"></i>
                <span class="nav-link-txt">Riwayat</span>
            </a>
        </div>

        <div class="nav-right">
            <div class="chip">
                <div class="chip-av">{{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}</div>
                <span>{{ auth()->user()->nama_lengkap ?? 'User' }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin:0">
                @csrf
                <button type="submit" class="btn btn-s btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="nav-link-txt">Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    {{-- Content --}}
    <div class="user-wrap">
        <div class="ucontent">
            @yield('content')
        </div>
    </div>

    {{-- Toast Trigger Session --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () =>
                showToast('success', 'Berhasil!', '{{ session('success') }}')
            );
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () =>
                showToast('danger', 'Gagal!', '{{ session('error') }}')
            );
        </script>
    @endif

    @yield('scripts')
    <script>
        /* Toast */
        function showToast(type, title, msg, dur = 4000) {
            const wrap = document.getElementById('toast-wrap');
            const t = document.createElement('div');
            t.className = 'toast';
            const cls = type === 'success' ? 's' : type === 'danger' ? 'd' : 'i';
            const icon = type === 'success' ? '<i class="bi bi-check-lg"></i>'
                : type === 'danger' ? '<i class="bi bi-exclamation-circle"></i>'
                    : '<i class="bi bi-info-circle"></i>';
            t.innerHTML = `
                <div class="toast-icon ${cls}">${icon}</div>
                <div class="toast-body">
                    <div class="toast-title">${title}</div>
                    <div class="toast-msg">${msg}</div>
                </div>
                <button class="toast-x" onclick="dismissToast(this.closest('.toast'))">×</button>
                <div class="toast-progress"><div class="toast-bar ${cls}" style="width:100%"></div></div>
            `;
            wrap.appendChild(t);
            requestAnimationFrame(() => requestAnimationFrame(() => t.classList.add('in')));
            const bar = t.querySelector('.toast-bar');
            bar.style.transition = `width ${dur}ms linear`;
            requestAnimationFrame(() => requestAnimationFrame(() => bar.style.width = '0%'));
            t._timer = setTimeout(() => dismissToast(t), dur);
        }

        function dismissToast(t) {
            clearTimeout(t._timer);
            t.classList.add('out');
            t.addEventListener('transitionend', () => t.remove(), { once: true });
        }

        /* Modal Konfirmasi */
        let _formKonfirmasi = null;

        function konfirmasi(formId, pesan = 'Tindakan ini tidak bisa dibatalkan.') {
            _formKonfirmasi = document.getElementById(formId);
            document.getElementById('mk-pesan').textContent = pesan;
            const modal = document.getElementById('modal-konfirmasi');
            modal.style.display = 'flex';
            requestAnimationFrame(() => requestAnimationFrame(() => modal.classList.add('in')));
        }

        function tutupKonfirmasi() {
            const modal = document.getElementById('modal-konfirmasi');
            modal.classList.remove('in');
            modal.addEventListener('transitionend', () => {
                modal.style.display = 'none';
                _formKonfirmasi = null;
            }, { once: true });
        }

        document.getElementById('mk-btn-hapus').addEventListener('click', () => {
            if (_formKonfirmasi) _formKonfirmasi.submit();
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') tutupKonfirmasi();
        });
    </script>
</body>

</html>