@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <h1 class="auth-h">Selamat datang kembali</h1>
    <p class="auth-sub">Silakan masuk ke akun PT Londo Bell Anda</p>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="fg">
            <label class="fl">Email</label>
            <input type="email" name="email" class="fc {{ $errors->has('email') ? 'err' : '' }}" value="{{ old('email') }}"
                placeholder="contoh@gmail.com" autofocus required>
            @error('email')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
        <div class="fg">
            <label class="fl">Password</label>
            <div class="pw-wrap">
                <input type="password" id="pw-login" name="password" class="fc {{ $errors->has('password') ? 'err' : '' }}"
                    placeholder="************" required>
                <button type="button" class="pw-toggle" onclick="togglePw('pw-login', this)"
                    aria-label="Tampilkan password">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
            @error('password')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn-auth">Masuk →</button>
    </form>
    <div class="auth-div"><span>atau</span></div>
    <p class="auth-alt">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
@endsection