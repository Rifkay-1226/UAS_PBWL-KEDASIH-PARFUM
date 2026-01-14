@extends('layouts.app')

@section('title', $produk->nama_produk)

@section('content')
<div class="card">
    <div class="card-header">
        <h2>{{ $produk->nama_produk }}</h2>
        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-4">
                @php($imageUrl = \App\Helpers\ImageHelper::getImageUrl($produk->gambar))
                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $produk->nama_produk }}" class="img-fluid rounded shadow-sm mb-2">
                @else
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center text-secondary" style="width: 100%; height: 300px;">
                        <i class="fas fa-image mb-2" style="font-size: 3rem;"></i>
                        <p class="mb-0">Tidak ada gambar</p>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-semibold" style="width: 150px;">Kode Produk</td>
                        <td>{{ $produk->kode_produk }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Kategori</td>
                        <td><span class="badge bg-primary">{{ $produk->kategori->nama_kategori }}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Harga</td>
                        <td class="fs-5 fw-bold text-success">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Stok</td>
                        <td>
                            @php($isParfum = strtolower($produk->kategori->nama_kategori) === 'parfum')
                            @if($produk->stok > 10)
                                <span class="badge bg-success">{{ $produk->stok }} {{ $isParfum ? 'ml' : 'unit' }}</span>
                            @elseif($produk->stok > 0)
                                <span class="badge bg-warning text-dark">{{ $produk->stok }} {{ $isParfum ? 'ml' : 'unit' }}</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="mt-4">
                    <h5>Deskripsi</h5>
                    <p class="text-secondary mb-0">
                        {{ $produk->deskripsi ?? 'Tidak ada deskripsi' }}
                    </p>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('produk.edit', $produk) }}" class="btn btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('produk.destroy', $produk) }}" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
