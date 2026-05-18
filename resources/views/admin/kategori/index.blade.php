@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('content')

    <div class="ph">
        <div>
            <h1>Kelola Kategori</h1>
            <p>{{ $kategori->count() }} kategori terdaftar</p>
        </div>
    </div>

    {{-- Tambah Kategori --}}
    <div class="card" style="margin-bottom:16px">
        <div class="card-head"><span>Tambah Kategori</span></div>
        <div class="card-body">
            <form action="{{ route('admin.kategori.store') }}" method="POST"
                style="display:flex;gap:10px;align-items:flex-end">
                @csrf
                <div class="fg" style="margin:0;flex:1;min-width:0">
                    <label class="fl">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="fc {{ $errors->has('nama_kategori') ? 'err' : '' }}"
                        placeholder="Contoh: Elektronik..." value="{{ old('nama_kategori') }}" required>
                    @error('nama_kategori')<p class="err-msg">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn btn-p" style="flex-shrink:0;margin-bottom:4px">
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Kategori --}}
    <div class="tcard">
        <div class="tscroll">
            <table>
                <thead>
                    <tr>
                        <th style="width:36px">No</th>
                        <th>Nama Kategori</th>
                        <th style="width:130px">Jumlah Barang</th>
                        <th class="tr" style="width:70px">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $kat)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-weight:500">{{ $loop->iteration }}</td>
                            <td>
                                <form action="{{ route('admin.kategori.update', $kat) }}" method="POST"
                                    style="display:flex;align-items:center;gap:8px">
                                    @csrf @method('PUT')
                                    <input type="text" name="nama_kategori" value="{{ $kat->nama_kategori }}"
                                        style="flex:1;min-width:0;width:32px;height:32px;border:1px solid transparent;border-radius:var(--rs);font-size:13px;background:transparent;color:var(--txt);outline:none;transition:all .13s"
                                        onfocus="this.style.borderColor='var(--blue)';this.style.background='var(--white)';this.style.boxShadow='0 0 0 3px rgba(59,126,245,.1)'"
                                        onblur="this.style.borderColor='transparent';this.style.background='transparent';this.style.boxShadow='none'">
                                    <button type="submit"
                                        style="display:inline-flex;align-items:center;gap:4px;padding:0 10px;height:32px;border-radius:var(--rs);font-size:12px;font-weight:500;color:var(--g-tx);background:var(--g-bg);border:1px solid var(--g-b);cursor:pointer;transition:all .13s;white-space:nowrap;flex-shrink:0"
                                        onmouseover="this.style.background='var(--g-b)'"
                                        onmouseout="this.style.background='var(--g-bg)'">
                                        <i class="bi bi-check-lg"></i> Simpan
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if($kat->barang_count > 0)
                                    <span class="b bb">{{ $kat->barang_count }} barang</span>
                                @else
                                    <span class="b bn">0 barang</span>
                                @endif
                            </td>
                            <td class="tr">
                                <form id="hapus-kat-{{ $kat->id }}" action="{{ route('admin.kategori.destroy', $kat) }}"
                                    method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                        onclick="konfirmasiHapus('hapus-kat-{{ $kat->id }}', '{{ addslashes($kat->nama_kategori) }}')"
                                        style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:var(--rs);color:var(--r-tx);background:var(--r-bg);border:1px solid var(--r-b);cursor:pointer;transition:all .13s"
                                        onmouseover="this.style.background='var(--r-b)'"
                                        onmouseout="this.style.background='var(--r-bg)'">
                                        <i class="bi bi-trash" style="font-size:12px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="tbl-empty">Belum ada kategori</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection