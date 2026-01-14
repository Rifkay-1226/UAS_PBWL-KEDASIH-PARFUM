@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Daftar Transaksi</h5>
    </div>
    <div class="card-body">
        @if($transaksi->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>No Transaksi</th>
                            <th>User</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $item)
                        <tr>
                            <td>{{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}</td>
                            <td><strong>{{ $item->no_transaksi }}</strong></td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->produk->nama_produk }}</td>
                            @php($isParfum = strpos(strtolower($item->produk->kategori->nama_kategori), 'parfum') !== false)
                            <td>{{ $item->jumlah }} {{ $isParfum ? 'ml' : 'unit' }}</td>
                            <td class="fw-semibold text-success">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('transaksi.show', $item) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye me-1"></i>Detail</a>
                                    <form method="POST" action="{{ route('transaksi.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Yakin hapus transaksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $transaksi->links() }}
            </div>
        @else
            <div class="text-center py-5 text-secondary">
                <i class="fas fa-inbox mb-3" style="font-size: 3rem;"></i>
                <p>Belum ada transaksi.</p>
            </div>
        @endif
    </div>
</div>
@endsection
