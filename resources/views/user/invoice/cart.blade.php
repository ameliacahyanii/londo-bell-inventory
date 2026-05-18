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
        <div class="grid-2col-r">

            {{-- Item List --}}
            <div class="card">
                <div class="card-head">
                    <span>Daftar Barang</span>
                    <span style="font-size:12px;color:var(--sub)">{{ count($cart) }} item</span>
                </div>
                <div class="card-body">
                    @foreach($cart as $id => $item)
                        @php $stok = $item['stok'] ?? 9999; @endphp
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
                                    style="width:44px;height:28px;text-align:center;padding:0 4px;font-weight:700;font-size:13px;-moz-appearance:textfield;appearance:textfield"
                                    value="{{ $item['kuantitas'] }}" min="1" max="{{ $stok }}" onchange="submitQty(this)"
                                    onwheel="this.blur()">

                                <button type="button" class="qb" onclick="stepQty(this, 1)">+</button>
                            </form>

                            <p class="ci-price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>

                            <form id="form-hapus-{{ $id }}" action="{{ route('user.cart.remove', $id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="ci-del" title="Hapus"
                                    onclick="konfirmasi('form-hapus-{{ $id }}', 'Hapus \'{{ $item['nama_barang'] }}\' dari keranjang?')">
                                    ×
                                </button>
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
        function syncBtns(form) {
            const input = form.querySelector('input[name="kuantitas"]');
            const [bMin, bPlus] = form.querySelectorAll('button.qb');
            const val = parseInt(input.value);
            const max = parseInt(input.max) || Infinity;

            const setBtn = (btn, disabled) => {
                btn.disabled = disabled;
                btn.style.opacity = disabled ? '.35' : '1';
                btn.style.cursor = disabled ? 'not-allowed' : 'pointer';
            };

            setBtn(bMin, val <= 1);
            setBtn(bPlus, val >= max);
        }

        function stepQty(btn, delta) {
            if (btn.disabled) return;

            const form = btn.closest('form');
            const input = form.querySelector('input[name="kuantitas"]');
            const max = parseInt(input.max) || Infinity;
            const prev = parseInt(input.value) || 1;
            const next = Math.min(Math.max(1, prev + delta), max);

            if (next === prev) return;
            input.value = next;
            syncBtns(form);
            form.submit();
        }

        function submitQty(input) {
            const form = input.closest('form');
            const max = parseInt(input.max) || Infinity;
            let val = parseInt(input.value);

            if (isNaN(val) || val < 1) val = 1;
            if (val > max) val = max;
            input.value = val;

            syncBtns(form);
            form.submit();
        }

        function konfirmasi(formId, pesan) {
            if (confirm(pesan)) document.getElementById(formId).submit();
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form.ci-qty').forEach(syncBtns);
        });
    </script>
@endsection