@extends('layouts.app')
@section('title', 'Buat Transaksi')
@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-cash-register me-2"></i>Buat Transaksi Baru</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transaksi.store') }}" id="formTransaksi" class="needs-validation" novalidate>
            @csrf
            <div class="card mb-4">
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pelanggan" class="form-label">Pelanggan</label>
                        <input type="text" class="form-control" id="pelanggan" name="pelanggan" value="{{ old('pelanggan') }}" required>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Daftar Produk</span>
                    <button type="button" class="btn btn-success btn-sm" id="addItem">Tambah Produk</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Produk</th>
                                    <th style="width: 20%">Harga</th>
                                    <th style="width: 20%">Jumlah</th>
                                    <th style="width: 15%">Subtotal</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTable">
                                <tr class="item-row" data-index="0">
                                    <td>
                                        <select name="items[0][produk_id]" class="form-select produk-select" required data-index="0">
                                            <option value="">Pilih Produk</option>
                                            @foreach($produks as $produk)
                                                <option value="{{ $produk->id }}" data-harga="{{ $produk->harga }}" data-kategori="{{ strtolower($produk->kategori->nama_kategori) }}">{{ $produk->nama_produk }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><span class="harga-display">-</span></td>
                                    <td>
                                        <input type="number" name="items[0][jumlah]" class="form-control jumlah-input" min="1" value="1" required>
                                    </td>
                                    <td><span class="subtotal-display">-</span></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Baris">&times;</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <strong>Grand Total: <span id="grandTotal">-</span></strong>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const produkList = @json($produkList);
    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.item-row');
        items.forEach((item, idx) => {
            const removeBtn = item.querySelector('.remove-item');
            if (removeBtn) {
                removeBtn.style.display = items.length > 1 ? 'block' : 'none';
                removeBtn.onclick = function() { item.remove(); updateTotal(); updateRemoveButtons(); };
            }
        });
    }
    function updateTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.produk-select');
            const jumlahInput = row.querySelector('.jumlah-input');
            const subtotalDisplay = row.querySelector('.subtotal-display');
            const hargaDisplay = row.querySelector('.harga-display');
            if (select && jumlahInput && subtotalDisplay && hargaDisplay) {
                const option = select.options[select.selectedIndex];
                const harga = parseInt(option.dataset.harga) || 0;
                const jumlah = parseInt(jumlahInput.value) || 0;
                const subtotal = harga * jumlah;
                hargaDisplay.textContent = harga ? formatRupiah(harga) : '-';
                subtotalDisplay.textContent = harga ? formatRupiah(subtotal) : '-';
                grandTotal += subtotal;
            }
        });
        document.getElementById('grandTotal').textContent = formatRupiah(grandTotal);
    }
    function bindEvents(row) {
        // Bind event hanya pada baris yang diberikan
        const select = row.querySelector('.produk-select');
        const jumlahInput = row.querySelector('.jumlah-input');
        if (select) {
            select.addEventListener('change', function() {
                updateTotal();
            });
        }
        if (jumlahInput) {
            jumlahInput.addEventListener('input', function() {
                updateTotal();
            });
        }
    }
    // Bind event pada baris awal
    document.querySelectorAll('.item-row').forEach(function(row) {
        bindEvents(row);
    });
    updateRemoveButtons();
    updateTotal();
    document.getElementById('addItem').addEventListener('click', function() {
        let produkOptions = '<option value="">Pilih Produk</option>';
        produkList.forEach(function(p) {
            produkOptions += `<option value="${p.id}" data-harga="${p.harga}" data-kategori="${p.kategori}">${p.nama}</option>`;
        });
        const newIndex = document.querySelectorAll('.item-row').length;
        const newRow = `
            <tr class="item-row" data-index="${newIndex}">
                <td>
                    <select name="items[${newIndex}][produk_id]" class="form-select produk-select" required data-index="${newIndex}">
                        ${produkOptions}
                    </select>
                </td>
                <td><span class="harga-display">-</span></td>
                <td>
                    <input type="number" name="items[${newIndex}][jumlah]" class="form-control jumlah-input" min="1" value="1" required>
                </td>
                <td><span class="subtotal-display">-</span></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Baris">&times;</button></td>
            </tr>
        `;
        document.querySelector('#itemsTable').insertAdjacentHTML('beforeend', newRow);
        const lastRow = document.querySelector('#itemsTable').lastElementChild;
        bindEvents(lastRow);
        updateRemoveButtons();
        updateTotal();
    });
});
</script>
@endsection
