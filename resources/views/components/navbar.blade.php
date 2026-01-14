<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('welcome') }}">🌹 Kedasih Parfum</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}">Beranda</a></li>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('produk.index') }}">Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">User</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('transaksi.index') }}">Transaksi</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('laporan') }}">Laporan</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('produk.list') }}">Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('transaksi.create') }}">Buat Transaksi</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('transaksi.my') }}">Transaksi Saya</a></li>
                    @endif
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-2">
                @auth
                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
