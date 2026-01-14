@extends('layouts.app')

@section('title', 'Login')

@section('content')
<style>
    /* Reset layout spacing from the main template when not authenticated */
    .d-flex {
        justify-content: center;
    }

    .main-content {
        margin-left: 0;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 2rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .auth-card {
        width: min(420px, 100%);
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        padding: 2.25rem;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .auth-title {
        text-align: center;
        margin-bottom: 1.5rem;
        color: #2d2d2d;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .auth-subtitle {
        text-align: center;
        margin-bottom: 2rem;
        color: #6b7280;
        font-size: 0.95rem;
    }

    .auth-form .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-bottom: 1.1rem;
    }

    .auth-form label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
    }

    .auth-form input[type="email"],
    .auth-form input[type="password"] {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background-color: #f9fafb;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .auth-form input:focus {
        outline: none;
        border-color: #667eea;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
    }

    .auth-form .form-remember {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.25rem;
    }

    .auth-form .error-text {
        color: #dc3545;
        font-size: 0.85rem;
    }

    .auth-submit {
        width: 100%;
        padding: 0.9rem 1rem;
        border: none;
        border-radius: 12px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        font-weight: 700;
        letter-spacing: 0.01em;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .auth-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.25);
    }

    .auth-submit:active {
        transform: translateY(0);
        box-shadow: none;
    }

    @media (max-width: 768px) {
        .auth-wrapper {
            padding: 1.25rem;
        }

        .auth-card {
            padding: 1.75rem;
            border-radius: 14px;
        }

        .auth-title {
            font-size: 1.4rem;
        }

        .auth-subtitle {
            font-size: 0.9rem;
        }

        .auth-form input[type="email"],
        .auth-form input[type="password"] {
            padding: 0.8rem 0.9rem;
        }

        .auth-submit {
            padding: 0.85rem 1rem;
            font-size: 1rem;
        }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Selamat Datang</h2>
        <p class="auth-subtitle">Masuk untuk mengelola Kedasih Parfum</p>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-remember">
                <input type="checkbox" id="remember" name="remember" value="on">
                <label for="remember" class="mb-0">Ingat saya</label>
            </div>

            <button type="submit" class="auth-submit mt-3">Login</button>
        </form>
    </div>
</div>
@endsection
