@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-box me-2"></i>Tambah Produk Baru</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option 
                                value="{{ $kategori->id }}" 
                                data-is-parfum="{{ strtolower($kategori->nama_kategori) === 'parfum' ? 1 : 0 }}"
                                {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}
                            >
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="nama_produk" class="form-label">Nama </label>
                    <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" class="form-control @error('nama_produk') is-invalid @enderror" required>
                    @error('nama_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="harga" class="form-label">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga" value="{{ old('harga') }}" step="0.01" class="form-control @error('harga') is-invalid @enderror" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="stok" class="form-label">Stok <span id="stok-unit-label" class="text-muted">(unit)</span></label>
                    <input type="number" id="stok" name="stok" value="{{ old('stok') }}" class="form-control @error('stok') is-invalid @enderror" required>
                    <div class="form-text" id="stok-help">Masukkan jumlah stok dalam unit.</div>
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="gambar" class="form-label">Gambar Produk</label>
                    <input type="file" id="gambar" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

            @push('scripts')
            <script>
                (function() {
                    const kategoriSelect = document.getElementById('kategori_id');
                    const stokLabel = document.getElementById('stok-unit-label');
                    const stokInput = document.getElementById('stok');
                    const stokHelp = document.getElementById('stok-help');

                    function updateStokUnit() {
                        const opt = kategoriSelect.options[kategoriSelect.selectedIndex];
                        const isParfum = opt && opt.getAttribute('data-is-parfum') === '1';
                        if (isParfum) {
                            stokLabel.textContent = '(ml)';
                            stokHelp.textContent = 'Masukkan stok dalam mililiter (ml).';
                            stokInput.setAttribute('step', '1');
                            stokInput.setAttribute('min', '0');
                            stokInput.placeholder = 'contoh: 50 (untuk 50 ml)';
                        } else {
                            stokLabel.textContent = '(unit)';
                            stokHelp.textContent = 'Masukkan jumlah stok dalam unit.';
                            stokInput.setAttribute('step', '1');
                            stokInput.setAttribute('min', '0');
                            stokInput.placeholder = 'contoh: 10';
                        }
                    }

                    kategoriSelect.addEventListener('change', updateStokUnit);
                    updateStokUnit();
                })();
            </script>
            @endpush
