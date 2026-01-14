@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="register-container">
    <div class="card">
        <div class="card-body register-card-body">
            <h2 class="register-title">Daftar Akun</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-success register-btn">Daftar</button>

                <p class="register-link">
                    Sudah punya akun? <a href="{{ route('login') }}" class="register-link-a">Login di sini</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
