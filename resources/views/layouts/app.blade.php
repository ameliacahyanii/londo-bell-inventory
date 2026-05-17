<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | PT Londo Bell</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,700;0,800;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

    <div class="ovl" id="ovl" onclick="closeSb()"></div>

    <aside class="sb" id="sb">
        <div class="sb-top">
            <div class="sb-logo-row">
                <div class="sb-brand">
                    <div class="sb-brand-dot">
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                            <rect width="20" height="20" rx="5" fill="#3b7ef5" />
                            <path d="M5 14V6h3.5a2.5 2.5 0 010 5H5M11 6h3a3 3 0 010 6h-3V6z" stroke="white"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                        </svg>
                    </div>
                    Londo Bell
                </div>
                <button class="sb-x" onclick="closeSb()">
                    <svg viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <span class="sb-role">Panel Admin</span>
        </div>

        <p class="sb-grp">Menu Utama</p>
        <ul class="sb-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.barang.index') }}"
                    class="{{ request()->routeIs('admin.barang.*') ? 'on' : '' }}">
                    <i class="bi bi-box-seam"></i> Barang
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kategori.index') }}"
                    class="{{ request()->routeIs('admin.kategori.*') ? 'on' : '' }}">
                    <i class="bi bi-tag"></i> Kategori
                </a>
            </li>
        </ul>

        <div class="sb-foot">
            <div class="sb-user">
                <div class="sb-av">{{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="sb-uname">{{ auth()->user()->nama_lengkap ?? 'Admin' }}</div>
                    <div class="sb-urole">Administrator</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sb-out">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="tbar">
        <button class="tbar-menu" onclick="openSb()">
            <svg viewBox="0 0 24 24">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>
        <span class="tbar-title">@yield('title')</span>
        <div class="tbar-space"></div>
    </div>

    <div class="admin-wrap">
        <div class="content">
            @if(session('success'))
                <div class="al al-s">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button class="al-x" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            @if(session('error'))
                <div class="al al-d">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button class="al-x" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="modal-hapus"
        style="display:none;position:fixed;inset:0;z-index:999;align-items:center;justify-content:center">
        <div style="position:absolute;inset:0;background:rgba(15,30,53,.45);backdrop-filter:blur(4px)"
            onclick="tutupModal()"></div>
        <div
            style="position:relative;background:var(--white);border-radius:14px;padding:28px 28px 24px;width:100%;max-width:380px;margin:0 16px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div
                style="width:44px;height:44px;border-radius:12px;background:var(--r-bg);display:flex;align-items:center;justify-content:center;margin-bottom:16px">
                <i class="bi bi-trash3" style="font-size:20px;color:var(--r-tx)"></i>
            </div>
            <h3 style="font-size:16px;font-weight:700;color:var(--txt);margin-bottom:6px">Hapus data ini?</h3>
            <p id="modal-hapus-pesan" style="font-size:13px;color:var(--sub);line-height:1.6;margin-bottom:24px">
                Tindakan ini tidak bisa dibatalkan.</p>
            <div style="display:flex;gap:8px;justify-content:flex-end">
                <button onclick="tutupModal()"
                    style="padding:8px 18px;border-radius:var(--rs);font-size:13px;font-weight:500;background:var(--bg);color:var(--txt);border:1px solid var(--bdr);cursor:pointer;transition:all .13s"
                    onmouseover="this.style.background='var(--bdr)'" onmouseout="this.style.background='var(--bg)'">
                    Batal
                </button>
                <button id="modal-hapus-btn"
                    style="padding:8px 18px;border-radius:var(--rs);font-size:13px;font-weight:500;background:var(--r-tx);color:#fff;border:none;cursor:pointer;transition:all .13s"
                    onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>


    <script>
        function openSb() {
            document.getElementById('sb').classList.add('on');
            document.getElementById('ovl').classList.add('on');
        }
        function closeSb() {
            document.getElementById('sb').classList.remove('on');
            document.getElementById('ovl').classList.remove('on');
        }

        // Modal hapus
        let _formHapus = null;

        function konfirmasiHapus(formId, nama) {
            _formHapus = document.getElementById(formId);
            document.getElementById('modal-hapus-pesan').textContent =
                'Data "' + nama + '" akan dihapus permanen dan tidak bisa dikembalikan.';
            const modal = document.getElementById('modal-hapus');
            modal.style.display = 'flex';
            setTimeout(() => modal.querySelector('div:nth-child(2)').style.opacity = 1, 10);
        }

        function tutupModal() {
            document.getElementById('modal-hapus').style.display = 'none';
            _formHapus = null;
        }

        document.getElementById('modal-hapus-btn').addEventListener('click', function () {
            if (_formHapus) _formHapus.submit();
        });

        // Tutup modal pakai ESC
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') tutupModal();
        });
    </script>

</body>

</html>