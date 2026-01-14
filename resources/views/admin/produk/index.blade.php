@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Daftar Produk</h2>
        <a href="{{ route('produk.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i> Tambah Produk</a>
    </div>

    <div class="card-body">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                        <tr>
                            <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
                            <td><strong>{{ $product->kode_produk }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @php($imageUrl = \App\Helpers\ImageHelper::getImageUrl($product->gambar))
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $product->nama_produk }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-secondary" style="width: 40px; height: 40px; font-size: 0.7rem;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                    <strong>{{ $product->nama_produk }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $product->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                @php($isParfum = strtolower($product->kategori->nama_kategori) === 'parfum')
                                @if($product->stok > 10)
                                    <span class="badge bg-success">{{ $product->stok }} {{ $isParfum ? 'ml' : 'unit' }}</span>
                                @elseif($product->stok > 0)
                                    <span class="badge bg-warning text-dark">{{ $product->stok }} {{ $isParfum ? 'ml' : 'unit' }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('produk.show', $product) }}" class="btn btn-outline-info btn-sm">Lihat</a>
                                    <a href="{{ route('produk.edit', $product) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('produk.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5 text-secondary">Belum ada produk. <a href="{{ route('produk.create') }}">Buat yang pertama</a></div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
@endsection