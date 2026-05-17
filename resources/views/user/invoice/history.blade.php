@extends('layouts.user')
@section('title', 'Riwayat Faktur')
@section('content')

    <div class="ph">
        <div>
            <h1>Riwayat Faktur</h1>
            <p>Semua faktur pembelian kamu</p>
        </div>
        <a href="{{ route('user.catalog') }}" class="btn btn-p">
            <svg viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Belanja Lagi
        </a>
    </div>

    <div class="tcard">
        <div class="tscroll">
            <table>
                <thead>
                    <tr>
                        <th style="width:36px">No</th>
                        <th>No. Invoice</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th class="tr" style="width:80px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faktur as $f)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:700">{{ $f->nomor_invoice }}</td>
                            <td style="font-weight:700;color:var(--blue)">
                                Rp {{ number_format($f->total_harga, 0, ',', '.') }}
                            </td>
                            <td style="color:var(--sub);font-size:12.5px">
                                {{ $f->created_at->format('d M Y') }}
                            </td>
                            <td class="tr">
                                <a href="{{ route('user.faktur.show', $f) }}" class="btn btn-s btn-sm">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="tbl-empty">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                    </svg>
                                    Belum ada riwayat faktur.
                                    <a href="{{ route('user.catalog') }}"
                                        style="color:var(--blue);font-weight:600;margin-left:4px">Belanja sekarang</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection