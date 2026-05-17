@extends('layouts.user')
@section('title', 'Keranjang Faktur')
@section('content')

    <div class="ph">
        <div>
            <h1>Keranjang</h1>
            <p>{{ count($cart) }} jenis barang dipilih</p>
        </div>
        <a href="{{ route('user.catalog') }}" class="btn btn-s">
            <svg viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7" />
            </svg>
            Lanjut Belanja
        </a>
    </div>

    @if(empty($cart))
        <div class="card">
            <div style="text-align:center;padding:64px 24px">
                <svg viewBox="0 0 24 24"
                    style="width:48px;height:48px;stroke:#d5cec3;fill:none;stroke-width:1.5;display:block;margin:0 auto 14px">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <path d="M16 10a4 4 0 01-8 0" />
                </svg>
                <p style="font-weight:600;margin-bottom:5px">Keranjang masih kosong</p>
                <p style="font-size:13px;color:var(--sub);margin-bottom:18px">Pilih produk dari katalog untuk mulai belanja.</p>
                <a href="{{ route('user.catalog') }}" class="btn btn-p">Lihat Katalog</a>
            </div>
        </div>
    @else
        <div style="display:grid;grid-template-columns:1fr 280px;gap:16px;align-items:start">
            {{-- Item List --}}
            <div class="card">
                <div class="card-head">
                    <span>Daftar Barang</span>
                    <span style="font-size:12px;color:var(--sub)">{{ count($cart) }} item</span>
                </div>
                <div class="card-body">
                    @foreach($cart as $id => $item)
                        <div class="ci">
                            <div class="ci-info">
                                <p class="ci-name">{{ $item['nama_barang'] }}</p>
                                <p class="ci-cat">{{ $item['kategori'] }}</p>
                            </div>

                            {{-- Qty Update --}}
                            <form action="{{ route('user.cart.update', $id) }}" method="POST" class="ci-qty">
                                @csrf @method('PUT')
                                <button type="button" class="qb" onclick="stepQty(this, -1)">−</button>
                                <input type="number" name="kuantitas" class="qv fc"
                                    style="width:44px;height:28px;text-align:center;padding:0 4px;font-weight:700;font-size:13px"
                                    value="{{ $item['kuantitas'] }}" min="1" onchange="this.form.submit()">
                                <button type="button" class="qb" onclick="stepQty(this, 1)">+</button>
                            </form>

                            <p class="ci-price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>

                            <form action="{{ route('user.cart.remove', $id) }}" method="POST"
                                onsubmit="return confirm('Hapus dari keranjang?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="ci-del" title="Hapus">×</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Ringkasan --}}
            <div style="position:sticky;top:70px">
                <div class="card">
                    <div class="card-head"><span>Ringkasan</span></div>
                    <div class="card-body">
                        @foreach($cart as $item)
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:7px;gap:8px">
                                <span style="color:var(--sub);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    {{ $item['kuantitas'] }}× {{ $item['nama_barang'] }}
                                </span>
                                <span style="font-weight:600;white-space:nowrap;flex-shrink:0">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach

                        <div
                            style="border-top:1px solid var(--bdr);margin:12px 0;padding-top:12px;display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;font-weight:700">Total</span>
                            <span style="font-size:20px;font-weight:800;color:var(--blue)">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('user.faktur.create') }}" class="btn btn-p btn-bl btn-lg">
                            <svg viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            Buat Faktur
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @endif

@endsection
@section('scripts')
    <script>
        function stepQty(btn, delta) {
            const form = btn.closest('form');
            const input = form.querySelector('input[name=kuantitas]');
            const val = parseInt(input.value) + delta;
            if (val >= 1) { input.value = val; form.submit(); }
        }
    </script>
@endsection