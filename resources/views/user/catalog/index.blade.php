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
            <form method="GET" action="{{ route('user.catalog') }}">
                <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">

                    {{-- Search --}}
                    <div style="flex:1;min-width:180px">
                        <label class="fl">Cari Barang</label>
                        <div style="position:relative">
                            <svg viewBox="0 0 24 24"
                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:15px;height:15px;stroke:var(--sub);fill:none;stroke-width:2;pointer-events:none">
                                <circle cx="11" cy="11" r="8" />
                                <path d="M21 21l-4.35-4.35" />
                            </svg>
                            <input type="text" name="search" class="fc" style="padding-left:32px"
                                placeholder="Ketik nama produk..." value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div style="width:180px">
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

                    {{-- Actions --}}
                    <div style="display:flex;gap:6px;padding-bottom:1px">
                        <button type="submit" class="btn btn-p">
                            <svg viewBox="0 0 24 24"
                                style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5">
                                <circle cx="11" cy="11" r="8" />
                                <path d="M21 21l-4.35-4.35" />
                            </svg>
                            Cari
                        </button>
                        @if(request('search') || request('kategori'))
                            <a href="{{ route('user.catalog') }}" class="btn btn-s">
                                <svg viewBox="0 0 24 24"
                                    style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5">
                                    <path d="M18 6L6 18M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>

                </div>

                {{-- Active filter badge --}}
                @if(request('search') || request('kategori'))
                    <div style="margin-top:10px;display:flex;gap:6px;flex-wrap:wrap;align-items:center">
                        <span style="font-size:11.5px;color:var(--sub)">Filter aktif:</span>
                        @if(request('search'))
                            <span
                                style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;background:var(--blue-bg,#eff6ff);color:var(--blue);padding:3px 10px;border-radius:99px">
                                "{{ request('search') }}"
                                <a href="{{ route('user.catalog', array_merge(request()->except('search'), ['kategori' => request('kategori')])) }}"
                                    style="color:inherit;line-height:1;text-decoration:none;opacity:.6;font-size:14px">&times;</a>
                            </span>
                        @endif
                        @if(request('kategori'))
                            @php $activeKat = $kategori->find(request('kategori')); @endphp
                            @if($activeKat)
                                <span
                                    style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;background:var(--blue-bg,#eff6ff);color:var(--blue);padding:3px 10px;border-radius:99px">
                                    {{ $activeKat->nama_kategori }}
                                    <a href="{{ route('user.catalog', array_merge(request()->except('kategori'), ['search' => request('search')])) }}"
                                        style="color:inherit;line-height:1;text-decoration:none;opacity:.6;font-size:14px">&times;</a>
                                </span>
                            @endif
                        @endif
                    </div>
                @endif

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
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px">
            @foreach($barang as $item)
                <div style="background:var(--white);border:1px solid var(--bdr);border-radius:12px;overflow:hidden;display:flex;flex-direction:column;transition:box-shadow .15s,transform .15s"
                    onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)';this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)'">

                    {{-- Gambar --}}
                    <div
                        style="aspect-ratio:4/3;background:var(--bg);display:flex;align-items:center;justify-content:center;overflow:hidden">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_barang }}"
                                style="width:100%;height:100%;object-fit:cover">
                        @else
                            <svg viewBox="0 0 24 24" style="width:36px;height:36px;stroke:var(--bdr);fill:none;stroke-width:1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <path d="M21 15l-5-5L5 21" />
                            </svg>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div style="padding:12px 14px 14px;display:flex;flex-direction:column;flex:1;gap:4px">
                        <span
                            style="font-size:11px;font-weight:600;color:var(--blue);background:var(--blue-bg,#eff6ff);padding:2px 8px;border-radius:99px;align-self:flex-start;letter-spacing:.2px">
                            {{ $item->kategori->nama_kategori }}
                        </span>
                        <p style="font-size:13.5px;font-weight:600;color:var(--txt);margin:2px 0 0;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"
                            title="{{ $item->nama_barang }}">
                            {{ $item->nama_barang }}
                        </p>
                        <p style="font-size:15px;font-weight:800;color:var(--blue);margin:2px 0 0">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </p>

                        <div style="flex:1"></div>

                        @if($item->jumlah <= 0)
                            {{-- Habis --}}
                            <p
                                style="font-size:11.5px;font-weight:600;color:#c0392b;background:#fff0f0;padding:3px 8px;border-radius:6px;text-align:center;margin-top:6px">
                                Stok habis
                            </p>
                            <button disabled
                                style="margin-top:6px;width:100%;padding:8px;border-radius:8px;border:1px solid var(--bdr);background:var(--bg);color:var(--sub);font-size:12.5px;font-weight:500;cursor:not-allowed">
                                Tidak tersedia
                            </button>
                        @else
                            {{-- Stok --}}
                            <p
                                style="font-size:11.5px;margin-top:4px;
                                                                                                            {{ $item->jumlah <= 5 ? 'color:#b45309;font-weight:600' : 'color:var(--sub)' }}">
                                @if($item->jumlah <= 5)
                                    ⚠ Sisa {{ $item->jumlah }} unit
                                @else
                                    Stok: {{ $item->jumlah }}
                                @endif
                            </p>

                            {{-- Form --}}
                            <form action="{{ route('user.catalog.addToCart', $item) }}" method="POST" style="margin-top:10px">
                                @csrf

                                {{-- Qty Stepper --}}
                                <div
                                    style="display:flex;align-items:center;justify-content:space-between;border:1px solid var(--bdr);border-radius:8px;overflow:hidden;margin-bottom:8px">
                                    <button type="button" onclick="stepCatQty(this,-1)"
                                        style="width:36px;height:32px;border:none;border-right:1px solid var(--bdr);background:var(--bg);color:var(--sub);font-size:18px;font-weight:400;cursor:not-allowed;transition:all .13s;display:flex;align-items:center;justify-content:center;opacity:.35"
                                        disabled
                                        onmouseover="if(!this.disabled){this.style.background='var(--bdr)';this.style.color='var(--txt)'}"
                                        onmouseout="this.style.background='var(--bg)';this.style.color='var(--sub)'">
                                        −
                                    </button>

                                    <input type="number" name="kuantitas" value="1" min="1" max="{{ $item->jumlah }}" required
                                        style="flex:1;height:32px;text-align:center;border:none;font-size:13px;font-weight:700;background:var(--white);color:var(--txt);padding:0;-moz-appearance:textfield;appearance:textfield"
                                        oninput="syncCatQtyBtns(this)" onwheel="this.blur()">

                                    <button type="button" onclick="stepCatQty(this,1)"
                                        style="width:36px;height:32px;border:none;border-left:1px solid var(--bdr);background:var(--bg);color:var(--sub);font-size:18px;font-weight:400;cursor:pointer;transition:all .13s;display:flex;align-items:center;justify-content:center{{ $item->jumlah <= 1 ? ';opacity:.35' : '' }}"
                                        {{ $item->jumlah <= 1 ? 'disabled' : '' }}
                                        onmouseover="if(!this.disabled){this.style.background='var(--bdr)';this.style.color='var(--txt)'}"
                                        onmouseout="this.style.background='var(--bg)';this.style.color='var(--sub)'">
                                        +
                                    </button>
                                </div>

                                {{-- Tombol Keranjang --}}
                                <button type="submit"
                                    style="width:100%;height:34px;border-radius:8px;border:none;background:var(--blue);color:#fff;font-size:12.5px;font-weight:600;cursor:pointer;transition:opacity .15s;display:flex;align-items:center;justify-content:center;gap:5px"
                                    onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                                    <i class="bi bi-cart-plus" style="font-size:13px"></i>
                                    Tambah ke Keranjang
                                </button>

                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:16px">{{ $barang->links() }}</div>
    @endif
@endsection

@section('scripts')
<script>
    function stepCatQty(btn, delta) {
        const wrapper = btn.closest('div');
        const input = wrapper.querySelector('input[name=kuantitas]');
        const max = parseInt(input.max) || Infinity;
        const newVal = Math.min(Math.max(1, parseInt(input.value || 1) + delta), max);
        input.value = newVal;
        updateCatBtns(wrapper, newVal, max);
    }

    function syncCatQtyBtns(input) {
        const wrapper = input.closest('div');
        const max = parseInt(input.max) || Infinity;
        let val = parseInt(input.value);
        if (isNaN(val) || val < 1) val = 1;
        if (val > max) val = max;
        input.value = val;
        updateCatBtns(wrapper, val, max);
    }

    function updateCatBtns(wrapper, val, max) {
        const [btnMin, btnPlus] = wrapper.querySelectorAll('button');

        const disableMinus = val <= 1;
        btnMin.disabled = disableMinus;
        btnMin.style.opacity = disableMinus ? '.35' : '1';
        btnMin.style.cursor = disableMinus ? 'not-allowed' : 'pointer';

        const disablePlus = val >= max;
        btnPlus.disabled = disablePlus;
        btnPlus.style.opacity = disablePlus ? '.35' : '1';
        btnPlus.style.cursor = disablePlus ? 'not-allowed' : 'pointer';
    }
</script>
@endsectionf