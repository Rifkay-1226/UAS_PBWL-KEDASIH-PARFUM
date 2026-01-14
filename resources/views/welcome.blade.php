<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kedasih Parfum - Toko Parfum Online</title>
    <!-- Custom styles moved to app.css -->
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1>🌹 Kedasih Parfum</h1>
            <ul class="nav-menu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="#about">Tentang</a></li>
            </ul>
            <div class="user-menu">
                @auth
                    <span>{{ Auth::user()->name }}</span>
                    <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-logout">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                @endauth
            </div>
        </div>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kedasih Parfum - Toko Parfum Online</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#home">🌹 Kedasih Parfum</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
                    </ul>
                    <div class="d-flex align-items-center gap-2">
                        @auth
                            <span class="fw-semibold">{{ Auth::user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <section class="py-5 bg-light" id="home">
            <div class="container text-center">
                <h2 class="display-5 mb-3">Selamat Datang di Kedasih Parfum</h2>
                <p class="lead mb-4">Toko Parfum Online Terpercaya dengan Koleksi Lengkap</p>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Kelola Toko</a>
                    @else
                        <a href="{{ route('produk.list') }}" class="btn btn-primary btn-lg">Lihat Produk</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Mulai Sekarang</a>
                @endauth
            </div>
        </section>

        <section class="py-5" id="features">
            <div class="container">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-4">
                        <div class="card h-100 text-center border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-4 mb-2">🌟</div>
                                <h5 class="card-title">Koleksi Lengkap</h5>
                                <p class="card-text">Kami menyediakan berbagai pilihan parfum dari brand ternama dengan kualitas terbaik.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 text-center border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-4 mb-2">💰</div>
                                <h5 class="card-title">Harga Kompetitif</h5>
                                <p class="card-text">Dapatkan harga terbaik dengan kualitas premium untuk produk-produk pilihan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 text-center border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-4 mb-2">📦</div>
                                <h5 class="card-title">Pengiriman Cepat</h5>
                                <p class="card-text">Pesanan Anda akan diproses dengan cepat dan aman hingga sampai tujuan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-white" id="about">
            <div class="container text-center">
                <h2 class="mb-3">Tentang Kami</h2>
                <p class="lead mb-0">
                    Kedasih Parfum adalah toko parfum online yang berkomitmen untuk memberikan produk berkualitas tinggi dengan harga terjangkau.<br>
                    Kami telah melayani ribuan pelanggan yang puas dengan koleksi parfum pilihan kami.
                </p>
            </div>
        </section>

        <footer class="bg-light py-4 mt-5 border-top">
            <div class="container text-center">
                <p class="mb-1">&copy; 2025 Kedasih Parfum. Semua hak cipta dilindungi.</p>
                <p class="mb-0 text-secondary">
                    Dibuat dengan <span class="text-danger">❤</span> menggunakan Laravel 12
                </p>
            </div>
        </footer>
    </body>
    </html>
