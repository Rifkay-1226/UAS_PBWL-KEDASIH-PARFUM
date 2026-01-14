@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Daftar Kategori</h2>
        <a href="{{ route('kategori.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i> Tambah Kategori</a>
    </div>
    <div class="card-body">
        @if($kategoris->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategoris as $kategori)
                        <tr>
                            <td>{{ ($kategoris->currentPage() - 1) * $kategoris->perPage() + $loop->iteration }}</td>
                            <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                            <td>{{ Str::limit($kategori->deskripsi, 50) ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $kategori->produk_count }}</span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('kategori.destroy', $kategori) }}" class="d-inline" onsubmit="return confirm('Yakin hapus kategori ini?');">
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
                {{ $kategoris->links() }}
            </div>
        @else
            <div class="text-center py-5 text-secondary">Belum ada kategori. <a href="{{ route('kategori.create') }}">Buat yang pertama</a></div>
        @endif
    </div>
</div>
@endsection
