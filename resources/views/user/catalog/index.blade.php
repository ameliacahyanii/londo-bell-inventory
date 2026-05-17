@extends('layouts.user')
@section('title', 'Katalog Barang')
@section('content')

    <div class="ph">
        <div>
            <h1>Katalog Barang</h1>
            <p>Temukan produk yang kamu butuhkan</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card" style="margin-bottom:16px">
        <div class="card-body" style="padding:14px 16px">
            <form method="GET" action="{{ route('user.catalog') }}"
                style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
                <div class="fg" style="margin:0;flex:1;min-width:160px">
                    <label class="fl">Cari Barang</label>
                    <input type="text" name="search" class="fc" placeholder="Ketik nama produk..."
                        value="{{ request('search') }}">
                </div>
                <div class="fg" style="margin:0;width:180px">
                    <label class="fl">Kategori</label>
                    <select name="kategori" class="fc">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:6px;margin-bottom:14px">
                    <button type="submit" class="btn btn-p">
                        <svg viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                        Cari
                    </button>
                    @if(request('search') || request('kategori'))
                        <a href="{{ route('user.catalog') }}" class="btn btn-s">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Grid --}}
    @if($barang->isEmpty())
        <div class="tbl-empty" style="padding:64px 0">
            <svg viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 01-8 0" />
            </svg>
            Barang tidak ditemukan
        </div>
    @else
        <div class="cat-grid">
            @foreach($barang as $item)
                <div class="cat-card">
                    <div class="cat-img">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_barang }}">
                        @else
                            <svg viewBox="0 0 24 24" style="width:36px;height:36px;stroke:#c5bfb5;fill:none;stroke-width:1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <path d="M21 15l-5-5L5 21" />
                            </svg>
                        @endif
                    </div>
                    <div class="cat-body">
                        <p class="cat-tag">{{ $item->kategori->nama_kategori }}</p>
                        <p class="cat-name" title="{{ $item->nama_barang }}">{{ $item->nama_barang }}</p>
                        <p class="cat-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>

                        @if($item->jumlah <= 0)
                            <p class="cat-oos">Stok habis</p>
                            <button class="cat-dis" disabled>Tidak tersedia</button>
                        @else
                            <p class="cat-stock">
                                @if($item->jumlah <= 5)
                                    ⚠ Sisa {{ $item->jumlah }} unit
                                @else
                                    Stok: {{ $item->jumlah }}
                                @endif
                            </p>
                            <form action="{{ route('user.catalog.addToCart', $item) }}" method="POST">
                                @csrf
                                <div class="cat-form">
                                    <input type="number" name="kuantitas" class="cat-qty" value="1" min="1" max="{{ $item->jumlah }}"
                                        required>
                                    <button type="submit" class="cat-add">+ Keranjang</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:16px">{{ $barang->links() }}</div>
    @endif
@endsection