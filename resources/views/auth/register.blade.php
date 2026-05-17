@extends('layouts.auth')
@section('title', 'Daftar Akun')
@section('content')
    <h1 class="auth-h">Buat akun baru</h1>
    <p class="auth-sub">Lengkapi data berikut untuk mulai menggunakan sistem</p>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="fg">
            <label class="fl">Nama Lengkap <span class="fl-hint">(3–40 huruf)</span></label>
            <input type="text" name="nama_lengkap" class="fc {{ $errors->has('nama_lengkap') ? 'err' : '' }}"
                value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap Anda" minlength="3" maxlength="40" required>
            @error('nama_lengkap')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
        <div class="fg">
            <label class="fl">Email <span class="fl-hint">(@gmail.com)</span></label>
            <input type="email" name="email" class="fc {{ $errors->has('email') ? 'err' : '' }}" value="{{ old('email') }}"
                placeholder="contoh@gmail.com" required>
            @error('email')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
        <div class="fg">
            <label class="fl">Nomor HP <span class="fl-hint">(diawali 08)</span></label>
            <input type="text" name="nomor_hp" class="fc {{ $errors->has('nomor_hp') ? 'err' : '' }}"
                value="{{ old('nomor_hp') }}" placeholder="08xxxxxxxxxx" required>
            @error('nomor_hp')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
        <div class="fg">
            <label class="fl">Password <span class="fl-hint">(6–12 karakter)</span></label>
            <div class="pw-wrap">
                <input type="password" id="pw-reg" name="password" class="fc {{ $errors->has('password') ? 'err' : '' }}"
                    placeholder="************" minlength="6" maxlength="12" oninput="checkPw(this.value)" required>
                <button type="button" class="pw-toggle" onclick="togglePw('pw-reg', this)" aria-label="Tampilkan password">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
            @error('password')<div class="err-msg">{{ $message }}</div>@enderror
            <ul class="pw-rules">
                <li id="rule-min"><i class="bi bi-x-circle-fill"></i> Minimal 6 karakter</li>
                <li id="rule-max"><i class="bi bi-x-circle-fill"></i> Maksimal 12 karakter</li>
                <li id="rule-num"><i class="bi bi-x-circle-fill"></i> Mengandung angka</li>
                <li id="rule-upper"><i class="bi bi-x-circle-fill"></i> Mengandung huruf kapital</li>
            </ul>
        </div>
        <div class="fg">
            <label class="fl">Konfirmasi Password</label>
            <div class="pw-wrap">
                <input type="password" id="pw-confirm" name="password_confirmation" class="fc" placeholder="************"
                    required>
                <button type="button" class="pw-toggle" onclick="togglePw('pw-confirm', this)"
                    aria-label="Tampilkan password">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn-auth">Daftar Sekarang →</button>
    </form>
    <div class="auth-div"><span>atau</span></div>
    <p class="auth-alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
@endsection