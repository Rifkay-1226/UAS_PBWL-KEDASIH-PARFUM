@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Laporan Transaksi</h2>
        <a href="{{ route('export.laporan') }}" class="btn btn-success"><i class="fas fa-file-excel me-1"></i> Export Excel</a>
    </div>
    <div class="card-body">
        <div class="row row-cols-1 row-cols-md-4 g-3 mb-4">
            <div class="col">
                <div class="bg-light rounded p-3 h-100">
                    <div class="text-secondary small">Total Transaksi</div>
                    <div class="fs-3 fw-bold text-primary">{{ $transaksi->count() }}</div>
                </div>
            </div>
            <div class="col">
                <div class="bg-light rounded p-3 h-100">
                    <div class="text-secondary small">Total Pendapatan</div>
                    <div class="fs-3 fw-bold text-success">Rp {{ number_format($transaksi->sum('total'), 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col">
                <div class="bg-light rounded p-3 h-100">
                    <div class="text-secondary small">Rata-rata Transaksi</div>
                    <div class="fs-3 fw-bold text-warning">Rp {{ number_format($transaksi->count() > 0 ? $transaksi->sum('total') / $transaksi->count() : 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col">
                <div class="bg-light rounded p-3 h-100">
                    <div class="text-secondary small">Total Item</div>
                    <div class="fs-3 fw-bold text-danger">{{ $transaksi->sum('jumlah') }}</div>
                </div>
            </div>
        </div>
        @if($transaksi->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>No Transaksi</th>
                            <th>User</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $item->no_transaksi }}</strong></td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->produk->nama_produk }}</td>
                            <td>{{ $item->produk->kategori->nama_kategori }}</td>
                            @php($isParfum = strpos(strtolower($item->produk->kategori->nama_kategori), 'parfum') !== false)
                            <td>{{ $item->jumlah }} {{ $isParfum ? 'ml' : 'unit' }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="fw-semibold text-success">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 text-secondary">Belum ada transaksi untuk dilaporkan.</div>
        @endif
    </div>
</div>
@endsection
