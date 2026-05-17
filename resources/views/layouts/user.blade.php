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
            <a href="{{ route('user.faktur.create') }}"
                class="nav-link {{ request()->routeIs('user.faktur.create') ? 'on' : '' }}">
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
            @if(session('success'))
                <div class="al al-s">
                    <i class="bi bi-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button class="al-x" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            @if(session('error'))
                <div class="al al-d">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                    <button class="al-x" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>

</html>