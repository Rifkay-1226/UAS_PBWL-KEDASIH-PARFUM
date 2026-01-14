<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kedasih Parfum') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/icons8-parfume-60.png') }}">
    @stack('styles')
</head>
<body>
    @auth
    <button id="openSidebarBtn" class="btn btn-dark shadow-sm" type="button" style="position:fixed;top:16px;left:12px;z-index:2000;display:none;width:26px;height:28px;border-radius:25%;display:flex;align-items:center;justify-content:center;padding:0;font-size:1.1rem;" aria-label="Buka Sidebar">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Sidebar -->
    <nav id="sidebarNav" class="sidebar-fixed bg-dark min-vh-100" style="width:220px;position:fixed;top:0;left:0;z-index:1000;">
                <div class="d-flex flex-column h-100">
                    <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom" style="gap:10px;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-light rounded p-2"><i class="fas fa-store text-dark"></i></span>
                            <span class="fw-bold text-white">Kedasih Parfum</span>
                        </div>
                        <button id="closeSidebarBtn" class="btn btn-light shadow-sm d-flex align-items-center justify-content-center" type="button" style="width:28px;height:28px;border-radius:25%;padding:0;font-size:1.1rem;" aria-label="Tutup Sidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    <ul class="nav nav-pills flex-column mb-auto mt-3">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : 'text-white' }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item"><a href="{{ route('kategori.index') }}" class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : 'text-white' }}"><i class="fas fa-tags me-2"></i> Kategori</a></li>
                        <li class="nav-item"><a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.*') ? 'active' : 'text-white' }}"><i class="fas fa-box me-2"></i> Produk</a></li>
                        <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : 'text-white' }}"><i class="fas fa-users me-2"></i> User</a></li>
                        <li class="nav-item"><a href="{{ route('transaksi.index') }}" class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : 'text-white' }}"><i class="fas fa-exchange-alt me-2"></i> Transaksi</a></li>
                        <li class="nav-item"><a href="{{ route('restok.index') }}" class="nav-link {{ request()->routeIs('restok.*') ? 'active' : 'text-white' }}"><i class="fas fa-brain me-2"></i> Rekomendasi Restok</a></li>
                        <li class="nav-item"><a href="{{ route('laporan') }}" class="nav-link {{ request()->routeIs('laporan') ? 'active' : 'text-white' }}"><i class="fas fa-chart-bar me-2"></i> Laporan</a></li>
                        @else
                        <li class="nav-item"><a href="{{ route('produk.list') }}" class="nav-link text-white"><i class="fas fa-box me-2"></i> Daftar Produk</a></li>
                        <li class="nav-item"><a href="{{ route('transaksi.create') }}" class="nav-link text-white"><i class="fas fa-cash-register me-2"></i> Input Transaksi</a></li>
                        <li class="nav-item"><a href="{{ route('transaksi.my') }}" class="nav-link text-white"><i class="fas fa-history me-2"></i> Transaksi Saya</a></li>
                        @endif
                    </ul>
                    <div class="mt-auto p-3 border-top text-center">
                        <p class="mb-1 small text-light">{{ Auth::user()->name }}</p>
                        <span class="badge bg-{{ Auth::user()->isAdmin() ? 'success' : 'info' }}">
                            {{ Auth::user()->isAdmin() ? 'Admin' : 'Pegawai' }}
                        </span>
                    </div>
                </div>
    </nav>
    @endauth

    <!-- Main Content -->
    @auth
    <div id="mainContent" style="margin-left:220px;transition:margin-left 0.2s;">
    @endauth
        <main class="ps-md-0">
            <!-- Top Navbar -->
            @auth
            <nav class="navbar navbar-expand navbar-light bg-white shadow-sm mb-4 rounded">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary d-md-none" type="button" onclick="toggleSidebarMobile()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-flex align-items-center ms-auto">
                        <div class="bg-light border rounded px-3 py-2 me-4 d-flex align-items-center">
                            <i class="fas fa-cloud me-2 text-primary"></i>
                            <span id="current-time" class="fw-semibold"></span>
                            <span class="ms-2 text-secondary">
                                <i class="fas fa-thermometer-half me-1"></i>
                                27°C Berawan
                            </span>
                        </div>
                        <!-- Settings Dropdown -->
                        <div class="dropdown ms-2">
                            <button class="btn btn-light shadow-sm rounded-circle d-flex align-items-center justify-content-center px-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width:36px;height:36px;font-size:1.1rem;">
                                <i class="fas fa-user-circle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm" style="min-width:140px;">
                                <li class="px-3 py-2 text-center small text-muted">{{ Auth::user()->name }}</li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            @endauth

            <!-- Page Content -->
            <div class="container-fluid px-0">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    @auth
    </div>
    @endauth

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @auth
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.getElementById('sidebarNav');
        var openBtn = document.getElementById('openSidebarBtn');
        var closeBtn = document.getElementById('closeSidebarBtn');
        var mainContent = document.getElementById('mainContent');
        function updateSidebar() {
            if (sidebar.style.display === 'none') {
                openBtn.style.display = 'flex';
                mainContent.style.marginLeft = '0';
            } else {
                openBtn.style.display = 'none';
                mainContent.style.marginLeft = '220px';
            }
        }
        openBtn.addEventListener('click', function() {
            sidebar.style.display = '';
            updateSidebar();
        });
        closeBtn.addEventListener('click', function() {
            sidebar.style.display = 'none';
            updateSidebar();
        });
        // Inisialisasi
        sidebar.style.display = '';
        updateSidebar();
        // Bootstrap dropdown manual init
        var dropdownTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
        dropdownTriggerList.map(function (dropdownTriggerEl) {
            return new bootstrap.Dropdown(dropdownTriggerEl);
        });
    });
    </script>
    @endauth
    <script>
    // Only for updating time and searchTable
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        const dateString = now.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        const timeEl = document.getElementById('current-time');
        if (timeEl) {
            timeEl.textContent = `${timeString} ${dateString}`;
        }
    }
    updateTime();
    setInterval(updateTime, 60000);
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input ? input.value.toUpperCase() : '';
        const table = document.getElementById('dataTable');
        const tr = table ? table.getElementsByTagName('tr') : [];
        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td');
            let showRow = false;
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        showRow = true;
                        break;
                    }
                }
            }
            tr[i].style.display = showRow ? '' : 'none';
        }
    }
    </script>
    @stack('scripts')
</body>
</html>