@extends('layouts.app')
@section('title', 'Kelola Barang')
@section('content')
    <div class="ph">
        <div>
            <h1>Kelola Barang</h1>
            <p>{{ $barang->total() }} produk terdaftar</p>
        </div>
        <a href="{{ route('admin.barang.create') }}" class="btn btn-p">
            <svg viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Tambah Barang
        </a>
    </div>

    <div class="tcard">
        <div class="tscroll">
            <table>
                <thead>
                    <tr>
                        <th style="width:36px">No</th>
                        <th style="width:56px">Foto</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="tr" style="width:90px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                        <tr>
                            <td style="color:var(--sub);font-size:12px">{{ $loop->iteration }}</td>
                            <td>
                                <div class="thumb">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_barang }}">
                                    @else
                                        <svg viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <path d="M21 15l-5-5L5 21" />
                                        </svg>
                                    @endif
                                </div>
                            </td>
                            <td style="font-weight:600">{{ $item->nama_barang }}</td>
                            <td><span class="b bb">{{ $item->kategori->nama_kategori }}</span></td>
                            <td style="font-weight:600">Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>
                                @if($item->jumlah <= 0)
                                    <span class="b br">Habis</span>
                                @elseif($item->jumlah <= 5)
                                    <span class="b ba">{{ $item->jumlah }} sisa</span>
                                @else
                                    <span class="b bg">{{ $item->jumlah }}</span>
                                @endif
                            </td>
                            <td class="tr">
                                <div style="display:flex;gap:4px;justify-content:flex-end">
                                    <a href="{{ route('admin.barang.edit', $item) }}"
                                        style="display:inline-flex;align-items:center;gap:5px;padding:5px 10px;height:32px;border-radius:var(--rs);font-size:12px;font-weight:500;color:var(--a-tx);background:var(--a-bg);border:1px solid var(--a-b);text-decoration:none;transition:all .13s"
                                        onmouseover="this.style.background='var(--a-b)'"
                                        onmouseout="this.style.background='var(--a-bg)'">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form id="hapus-{{ $item->id }}" action="{{ route('admin.barang.destroy', $item) }}"
                                        method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                            onclick="konfirmasiHapus('hapus-{{ $item->id }}', '{{ addslashes($item->nama_barang) }}')"
                                            style="display:inline-flex;align-items:center;gap:5px;padding:5px 10px;height:32px;border-radius:var(--rs);font-size:12px;font-weight:500;color:var(--r-tx);background:var(--r-bg);border:1px solid var(--r-b);cursor:pointer;transition:all .13s"
                                            onmouseover="this.style.background='var(--r-b)'"
                                            onmouseout="this.style.background='var(--r-bg)'">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>

                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="tbl-empty">
                                    <svg viewBox="0 0 24 24">
                                        <rect x="2" y="3" width="20" height="14" rx="2" />
                                        <path d="M8 21h8M12 17v4" />
                                    </svg>
                                    Belum ada barang. <a href="{{ route('admin.barang.create') }}"
                                        style="color:var(--accent);font-weight:600">Tambah sekarang</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $barang->links() }}
@endsection