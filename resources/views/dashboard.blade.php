
@extends('layouts.app')
@section('content')

@if(Auth::user()->isAdmin())
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <div class="bg-primary-subtle rounded shadow-sm px-3 py-3 mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-12">
                        <h2 class="mb-1 fw-bold text-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </h2>
                        <div class="text-dark">Selamat datang, {{ Auth::user()->name }}!</div>
                    </div>
                    <div class="col-lg-4 col-12 text-lg-end text-start mt-2 mt-lg-0">
                        <span class="d-inline-block text-secondary">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Total Produk</div>
                                <div class="fs-3 fw-bold">{{ $total_produk }}</div>
                            </div>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fas fa-box fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Total Transaksi</div>
                                <div class="fs-3 fw-bold">{{ $total_transaksi }}</div>
                            </div>
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fas fa-exchange-alt fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Total User</div>
                                <div class="fs-3 fw-bold">{{ $total_users }}</div>
                            </div>
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Pendapatan Hari Ini</div>
                                <div class="fs-3 fw-bold">Rp {{ number_format($pendapatan_hari_ini, 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fas fa-money-bill-wave fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm rounded-4">
                        <div class="card-header bg-white d-flex align-items-center rounded-top-4" style="border-bottom: 1px solid #e9ecef;">
                            <i class="fas fa-history me-2 text-primary"></i>
                            <h5 class="mb-0">Transaksi Terbaru</h5>
                        </div>
                        <div class="card-body pb-3 pt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Transaksi</th>
                                            <th>Produk</th>
                                            <th>Pegawai</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksi_terbaru as $index => $transaction)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $transaction->kode_transaksi }}</strong></td>
                                            <td>{{ $transaction->produk->nama_produk ?? 'Produk tidak ditemukan' }}</td>
                                            <td>{{ $transaction->user->name ?? 'User tidak ditemukan' }}</td>
                                            <td>{{ $transaction->jumlah ?? $transaction->quantity ?? 0 }}</td>
                                            <td>Rp {{ number_format($transaction->total_harga ?? $transaction->total ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">
            <div class="bg-primary-subtle rounded shadow-sm px-3 py-3 mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-12">
                        <h2 class="mb-1 fw-bold text-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </h2>
                        <div class="text-dark">Selamat datang, {{ Auth::user()->name }}!</div>
                    </div>
                    <div class="col-lg-4 col-12 text-lg-end text-start mt-2 mt-lg-0">
                        <span class="d-inline-block text-secondary">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-cash-register me-2"></i>Transaksi Hari Ini
                            </h5>
                            <div class="text-center py-4">
                                <h1 class="display-4">{{ $transaksi_hari_ini }}</h1>
                                <p class="text-muted">Transaksi yang Anda input hari ini</p>
                            </div>
                            <a href="{{ route('transaksi.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i>Input Transaksi Baru
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>Stok Menipis
                            </h5>
                            <div class="text-center py-4">
                                <h1 class="display-4">{{ $produk_stok_rendah }}</h1>
                                <p class="text-muted">Produk dengan stok kurang dari 10</p>
                            </div>
                            <a href="{{ route('produk.list') }}" class="btn btn-danger w-100">
                                <i class="fas fa-box me-2"></i>Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection