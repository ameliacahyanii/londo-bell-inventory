@extends('layouts.user')
@section('title', 'Faktur #' . $faktur->nomor_invoice)
@section('content')

    <div class="ph no-print">
        <div>
            <h1>Detail Faktur</h1>
            <p>{{ $faktur->nomor_invoice }}</p>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('user.faktur.history') }}" class="btn btn-s">
                <svg viewBox="0 0 24 24">
                    <path d="M19 12H5M12 5l-7 7 7 7" />
                </svg>
                Riwayat
            </a>
            <button onclick="window.print()" class="btn btn-p">
                <svg viewBox="0 0 24 24">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                Cetak
            </button>
        </div>
    </div>

    <div class="inv-wrap">
        {{-- Header --}}
        <div class="inv-head">
            <div class="inv-row">
                <div class="inv-brand">
                    <div class="inv-brand-dot">L</div>
                    PT Londo Bell
                </div>
                <span class="inv-pip">FAKTUR</span>
            </div>
            <div class="inv-nums">
                <div>
                    <p class="inv-num-lbl">No. Invoice</p>
                    <p class="inv-num-val">{{ $faktur->nomor_invoice }}</p>
                </div>
                <div>
                    <p class="inv-num-lbl">Tanggal</p>
                    <p class="inv-num-val">{{ $faktur->created_at->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="inv-num-lbl">Jam</p>
                    <p class="inv-num-val">{{ $faktur->created_at->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="inv-body">
            <div class="inv-meta">
                <div>
                    <p class="inv-meta-lbl">Kepada</p>
                    <p class="inv-meta-val">{{ $faktur->user->nama_lengkap }}</p>
                    <p class="inv-meta-sub">{{ $faktur->user->email }}</p>
                </div>
                <div>
                    <p class="inv-meta-lbl">Alamat Pengiriman</p>
                    <p class="inv-meta-val" style="line-height:1.6">{{ $faktur->alamat_pengiriman }}</p>
                    <p class="inv-meta-sub">Kode Pos: {{ $faktur->kode_pos }}</p>
                </div>
            </div>

            <div class="tcard" style="margin-bottom:0">
                <div class="tscroll">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:32px">#</th>
                                <th>Barang</th>
                                <th>Kategori</th>
                                <th style="width:120px">Harga Satuan</th>
                                <th style="width:50px" class="tc">Qty</th>
                                <th style="width:130px" class="tr">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faktur->items as $i => $item)
                                <tr>
                                    <td style="color:var(--sub);font-size:12px">{{ $i + 1 }}</td>
                                    <td style="font-weight:600">{{ $item->barang->nama_barang }}</td>
                                    <td><span class="b bb">{{ $item->barang->kategori->nama_kategori }}</span></td>
                                    <td style="color:var(--sub);font-size:12.5px">
                                        Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="tc" style="font-weight:600">{{ $item->kuantitas }}</td>
                                    <td class="tr" style="font-weight:700">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="inv-foot">
            <p class="inv-total-lbl">Total Harga</p>
            <p class="inv-total-num">Rp {{ number_format($faktur->total_harga, 0, ',', '.') }}</p>
        </div>
        <div class="inv-thanks">
            Terima kasih telah berbelanja di PT Londo Bell!
        </div>
    </div>

@endsection
@section('scripts')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .ucontent {
                padding-top: 0 !important;
            }
        }
    </style>
@endsection