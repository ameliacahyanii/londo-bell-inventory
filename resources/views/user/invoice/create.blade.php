@extends('layouts.user')
@section('title', 'Buat Faktur')
@section('content')

    <div class="ph">
        <div>
            <h1>Buat Faktur</h1>
            <p>Lengkapi alamat pengiriman</p>
        </div>
        <a href="{{ route('user.faktur.index') }}" class="btn btn-s">
            <svg viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7" />
            </svg>
            Kembali ke Keranjang
        </a>
    </div>

    <div class="grid-2col-r">
        {{-- Form --}}
        <div class="card">
            <div class="card-head"><span>Data Pengiriman</span></div>
            <div class="card-body">
                <form action="{{ route('user.faktur.store') }}" method="POST">
                    @csrf

                    <div class="fg">
                        <label class="fl">Alamat Pengiriman <span class="fl-hint">(10–100 huruf)</span></label>
                        <textarea name="alamat_pengiriman" rows="4"
                            class="fc {{ $errors->has('alamat_pengiriman') ? 'err' : '' }}" minlength="10" maxlength="100"
                            required
                            placeholder="Jl. Contoh No.1, Kelurahan, Kecamatan, Kota...">{{ old('alamat_pengiriman') }}</textarea>
                        @error('alamat_pengiriman')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>

                    <div class="fg">
                        <label class="fl">Kode Pos <span class="fl-hint">(5 digit)</span></label>
                        <input type="text" name="kode_pos" class="fc {{ $errors->has('kode_pos') ? 'err' : '' }}"
                            value="{{ old('kode_pos') }}" maxlength="5" pattern="[0-9]{5}" placeholder="12345" required
                            style="max-width:140px">
                        @error('kode_pos')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn btn-p btn-lg" style="margin-top:4px">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                        Simpan & Buat Faktur
                    </button>
                </form>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div style="position:sticky;top:70px">
            <div class="card">
                <div class="card-head"><span>Ringkasan Pesanan</span></div>
                <div class="card-body">
                    @foreach($cart as $item)
                        <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:8px;gap:8px">
                            <div style="min-width:0">
                                <p
                                    style="font-weight:600;margin-bottom:1px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    {{ $item['nama_barang'] }}
                                </p>
                                <p style="color:var(--sub)">
                                    {{ $item['kuantitas'] }} × Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                </p>
                            </div>
                            <span style="font-weight:700;white-space:nowrap;flex-shrink:0">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach

                    <div
                        style="border-top:1px solid var(--bdr);margin-top:10px;padding-top:12px;display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:13px;font-weight:700">Total</span>
                        <span style="font-size:20px;font-weight:800;color:var(--blue)">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection