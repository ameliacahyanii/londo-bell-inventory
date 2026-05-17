@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('content')

    <div class="ph">
        <div>
            <a href="{{ route('admin.barang.index') }}"
                style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--sub);margin-bottom:6px;transition:color .13s"
                onmouseover="this.style.color='var(--blue)'" onmouseout="this.style.color='var(--sub)'">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h1>Tambah Barang</h1>
            <p>Isi data barang dengan lengkap</p>
        </div>
    </div>
    <div class="grid-2col-r">
        <div class="card">
            <div class="card-head"><span>Informasi Produk</span></div>
            <div class="card-body">
                <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data"
                    id="form-barang">
                    @csrf

                    <div class="fg">
                        <label class="fl">Kategori</label>
                        <select name="kategori_id" class="fc {{ $errors->has('kategori_id') ? 'err' : '' }}" required>
                            <option value="">— Pilih Kategori —</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>

                    <div class="fg">
                        <label class="fl">Nama Barang <span class="fl-hint">(5–80 huruf)</span></label>
                        <input type="text" name="nama_barang" class="fc {{ $errors->has('nama_barang') ? 'err' : '' }}"
                            value="{{ old('nama_barang') }}" minlength="5" maxlength="80" placeholder="Nama produk..."
                            required>
                        @error('nama_barang')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>

                    <div class="frow">
                        <div class="fg">
                            <label class="fl">Harga</label>
                            <div class="ig">
                                <span class="ig-t">Rp</span>
                                <input type="number" name="harga" class="fc {{ $errors->has('harga') ? 'err' : '' }}"
                                    value="{{ old('harga') }}" min="0" placeholder="0" required>
                            </div>
                            @error('harga')<p class="err-msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="fg">
                            <label class="fl">Jumlah Stok</label>
                            <input type="number" name="jumlah" class="fc {{ $errors->has('jumlah') ? 'err' : '' }}"
                                value="{{ old('jumlah') }}" min="0" placeholder="0" required>
                            @error('jumlah')<p class="err-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="fg">
                        <label class="fl">Foto Barang</label>
                        <input type="file" name="foto" class="fc {{ $errors->has('foto') ? 'err' : '' }}"
                            accept="image/jpg,image/jpeg,image/png,image/webp" onchange="previewFoto(this)">
                        <p class="hint">JPG, PNG, WebP · Maks 2MB</p>
                        @error('foto')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>

                    <div style="display:flex;gap:8px;margin-top:4px">
                        <button type="submit" class="btn btn-p">
                            <svg viewBox="0 0 24 24">
                                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                                <path d="M17 21v-8H7v8M7 3v5h8" />
                            </svg>
                            Simpan Barang
                        </button>
                        <a href="{{ route('admin.barang.index') }}" class="btn btn-s">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Preview --}}
        <div style="position:sticky;top:70px">
            <div class="card" style="overflow:hidden">
                <div id="foto-preview-wrap"
                    style="aspect-ratio:4/3;background:#ede8e0;display:flex;align-items:center;justify-content:center">
                    <svg viewBox="0 0 24 24" style="width:36px;height:36px;stroke:#c5bfb5;fill:none;stroke-width:1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <path d="M21 15l-5-5L5 21" />
                    </svg>
                </div>
                <div class="card-body" style="padding:12px 14px">
                    <p id="prev-kat"
                        style="font-size:10px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">
                        — Kategori —</p>
                    <p id="prev-nama" style="font-size:14px;font-weight:600;margin-bottom:4px;color:var(--txt)">Nama produk
                    </p>
                    <p id="prev-harga" style="font-size:18px;font-weight:800;color:var(--accent)">Rp 0</p>
                </div>
            </div>
            <p style="font-size:11px;color:var(--sub);text-align:center;margin-top:8px">Preview live</p>
        </div>

    </div>

@endsection
@section('scripts')
    <script>
        function previewFoto(input) {
            const wrap = document.getElementById('foto-preview-wrap');
            if (input.files && input.files[0]) {
                const r = new FileReader();
                r.onload = e => { wrap.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`; };
                r.readAsDataURL(input.files[0]);
            }
        }
        document.querySelector('[name=nama_barang]')?.addEventListener('input', e => {
            document.getElementById('prev-nama').textContent = e.target.value || 'Nama produk';
        });
        document.querySelector('[name=harga]')?.addEventListener('input', e => {
            const v = parseInt(e.target.value) || 0;
            document.getElementById('prev-harga').textContent = 'Rp ' + v.toLocaleString('id-ID');
        });
        document.querySelector('[name=kategori_id]')?.addEventListener('change', e => {
            const opt = e.target.options[e.target.selectedIndex];
            document.getElementById('prev-kat').textContent = opt.value ? opt.text : '— Kategori —';
        });
    </script>
@endsection