@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Detail Transaksi {{ $transaksi->no_transaksi }}</h5>
    </div>

    <div class="card-body">
        <div class="grid-2col-gap2">
            <div>
                <h6 class="mb-1rem-fw600-dark"><i class="fas fa-info-circle me-2"></i>Informasi Transaksi</h6>
                <table class="table-full-collapse">
                    <tr class="tr-border-ddd">
                        <td class="td-075-fw500" style="width: 150px;">No Transaksi</td>
                        <td class="td-075"><strong>{{ $transaksi->no_transaksi }}</strong></td>
                    </tr>
                    <tr class="tr-border-ddd">
                        <td class="td-075-fw500">User</td>
                        <td class="td-075">{{ $transaksi->user->name }}</td>
                    </tr>
                    <tr class="tr-border-ddd">
                        <td class="td-075-fw500">Tanggal</td>
                        <td class="td-075">{{ $transaksi->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <div>
                <h6 class="mb-1rem-fw600-dark"><i class="fas fa-box me-2"></i>Informasi Produk</h6>
                <table class="table-full-collapse">
                    <tr class="tr-border-ddd">
                        <td class="td-075-fw500" style="width: 150px;">Produk</td>
                        <td class="td-075">{{ $transaksi->produk->nama_produk }}</td>
                    </tr>
                    <tr class="tr-border-ddd">
                        <td class="td-075-fw500">Kategori</td>
                        <td class="td-075">
                            <span class="badge bg-primary">
                            {{ $transaksi->produk->kategori->nama_kategori }}
                            </span>
                        </td>
                    </tr>
                    <tr class="tr-border-ddd">
                        <td class="td-075-fw500">Harga Satuan</td>
                        <td class="td-075-green">Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="rounded-box">
            <h6 class="mb-1rem-fw600-dark"><i class="fas fa-calculator me-2"></i>Detail Produk</h6>
            
            @if($transaksi->details()->exists())
                {{-- Transaksi dengan multiple produk --}}
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th class="td-075">No</th>
                                <th class="td-075">Produk</th>
                                <th class="td-075">Kategori</th>
                                <th class="td-075">Harga Satuan</th>
                                <th class="td-075">Jumlah</th>
                                <th class="td-075" style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->details as $index => $detail)
                            <tr>
                                <td class="td-075">{{ $index + 1 }}</td>
                                <td class="td-075">{{ $detail->produk->nama_produk }}</td>
                                <td class="td-075">
                                    <span class="badge badge-primary">{{ $detail->produk->kategori->nama_kategori }}</span>
                                </td>
                                <td class="td-075">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="td-075">
                                    @php($isParfum = strtolower($detail->produk->kategori->nama_kategori) === 'parfum')
                                    {{ $detail->jumlah }} {{ $isParfum ? 'ml' : 'unit' }}
                                </td>
                                <td class="td-075-green" style="text-align: right;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="border-top2">
                    <table class="table-full-collapse">
                        <tr>
                            <td class="td-05-fw600">Grand Total</td>
                            <td class="td-05-green">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            @else
                {{-- Transaksi legacy (single product) --}}
                <table class="table-full-collapse">
                    <tr class="tr-border-ddd">
                        <td class="td-075-fw500" style="width: 200px;">Jumlah</td>
                        @php($isParfum = strpos(strtolower($transaksi->produk->kategori->nama_kategori), 'parfum') !== false)
                        <td class="td-075">{{ $transaksi->jumlah }} {{ $isParfum ? 'ml' : 'unit' }}</td>
                    </tr>
                    <tr class="tr-border-ddd">
                        <td class="td-075-fw500">Subtotal</td>
                        <td class="td-075">Rp {{ number_format($transaksi->harga_satuan * $transaksi->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="tr-border2">
                        <td class="td-1rem-green">Total</td>
                        <td class="td-1rem-green">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            @endif
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
            <form method="POST" action="{{ route('transaksi.destroy', $transaksi) }}" class="d-inline" onsubmit="return confirm('Yakin hapus transaksi ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
