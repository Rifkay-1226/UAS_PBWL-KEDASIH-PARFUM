@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-box me-2"></i>Daftar Produk</h5>
        <div class="d-flex align-items-center gap-2">
            <input type="text" class="form-control form-control-sm w-auto" id="searchProduct" placeholder="Cari produk...">
            <span class="badge bg-primary">{{ $produk->total() }} Produk</span>
        </div>
    </div>
    <div class="card-body">
        @if($produk->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($produk as $item)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @php($imageUrl = \App\Helpers\ImageHelper::getImageUrl($item->gambar))
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $item->nama_produk }}" class="card-img-top" style="height:220px;object-fit:cover;">
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="height:220px;">
                            <i class="fas fa-image fa-2x text-secondary"></i>
                            <span class="text-muted small">Tidak ada gambar</span>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1">{{ $item->nama_produk }}</h6>
                        <span class="badge bg-info text-dark mb-2"><i class="fas fa-tag me-1"></i>{{ $item->kategori->nama_kategori }}</span>
                        @if($item->deskripsi)
                        <p class="text-muted small mb-2">{{ Str::limit($item->deskripsi, 80) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="fw-bold text-success">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            <span>
                                @php($isParfum = strtolower($item->kategori->nama_kategori) === 'parfum')
                                @if($item->stok > 10)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>{{ $item->stok }} {{ $isParfum ? 'ml' : 'unit' }}</span>
                                @elseif($item->stok > 0)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>{{ $item->stok }} {{ $isParfum ? 'ml' : 'unit' }}</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Habis</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $produk->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-secondary mb-3"></i>
            <p class="mt-3 text-muted">Belum ada produk tersedia.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('searchProduct').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const productCards = document.querySelectorAll('.product-card');
        
        productCards.forEach(card => {
            const title = card.querySelector('.product-title').textContent.toLowerCase();
            const category = card.querySelector('.product-category').textContent.toLowerCase();
            const description = card.querySelector('.product-description')?.textContent.toLowerCase() || '';
            
            if (title.includes(searchValue) || category.includes(searchValue) || description.includes(searchValue)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection
