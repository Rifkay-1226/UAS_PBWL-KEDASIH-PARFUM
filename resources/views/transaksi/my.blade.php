@extends('layouts.app')

@section('title', 'Transaksi Saya')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Transaksi Saya</h5>
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Buat Transaksi</a>
    </div>
    <div class="card-body">
        @if($transaksi->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Transaksi</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $item)
                    <tr>
                        <td>{{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}</td>
                        <td><strong>{{ $item->no_transaksi }}</strong></td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        @php($isParfum = strpos(strtolower($item->produk->kategori->nama_kategori), 'parfum') !== false)
                        <td>{{ $item->jumlah }} {{ $isParfum ? 'ml' : 'unit' }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        <td class="text-muted small">{{ $item->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $transaksi->links() }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <p>Belum ada transaksi. <a href="{{ route('transaksi.create') }}" class="text-primary fw-bold">Buat yang pertama</a></p>
        </div>
        @endif
    </div>
</div>
@endsection
