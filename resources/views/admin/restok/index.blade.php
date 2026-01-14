@extends('layouts.app')

@section('title', 'Rekomendasi Restok Parfum')

@push('styles')

<style>
    .restok-wrap {
        max-width: 1200px;
        margin: 0 auto;
    }

    .restok-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .restok-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.75rem;
    }

    .restok-chip {
        border-radius: 999px;
        padding: 6px 12px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .restok-product {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .restok-product img {
        width: 44px;
        height: 44px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .restok-meta {
        font-size: 0.82rem;
        color: #6c757d;
        margin-top: 2px;
    }

    .restok-table th,
    .restok-table td {
        vertical-align: middle;
        padding: 0.9rem;
    }

    .restok-actions {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        align-items: center;
    }

    .metric-label {
        color: #6c757d;
        font-size: 0.78rem;
    }

    @media (max-width: 768px) {
        .restok-card-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .restok-actions {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>

@endpush

@section('content')
<div class="restok-wrap">
<div class="card card-custom">
    <div class="card-header">
        <div class="restok-card-head">
            <div>
                <h5 class="mb-1"><i class="fas fa-brain me-2"></i>Sistem Rekomendasi Restok Parfum</h5>
                <p class="mb-0 text-muted">Berbasis Fuzzy Logic dan data historis penjualan</p>
            </div>
            <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-2"></i>Refresh data
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="restok-metrics mb-3">
            <div class="card card-custom bg-danger text-white">
                <div class="card-body p-3">
                    <h6 class="mb-1">Prioritas Sangat Tinggi</h6>
                    <h3 class="mb-0">{{ collect($rekomendasi)->where('fuzzy.prioritas_label', 'Sangat Tinggi')->count() }}</h3>
                    <small>Butuh restok segera</small>
                </div>
            </div>
            <div class="card card-custom bg-warning text-dark">
                <div class="card-body p-3">
                    <h6 class="mb-1">Prioritas Tinggi</h6>
                    <h3 class="mb-0">{{ collect($rekomendasi)->where('fuzzy.prioritas_label', 'Tinggi')->count() }}</h3>
                    <small>Perlu diperhatikan</small>
                </div>
            </div>
            <div class="card card-custom bg-info text-white">
                <div class="card-body p-3">
                    <h6 class="mb-1">Prioritas Sedang</h6>
                    <h3 class="mb-0">{{ collect($rekomendasi)->where('fuzzy.prioritas_label', 'Sedang')->count() }}</h3>
                    <small>Pantau terus</small>
                </div>
            </div>
            <div class="card card-custom bg-success text-white">
                <div class="card-body p-3">
                    <h6 class="mb-1">Stok Aman</h6>
                    <h3 class="mb-0">{{ collect($rekomendasi)->whereIn('fuzzy.prioritas_label', ['Rendah', 'Sangat Rendah'])->count() }}</h3>
                    <small>Tidak perlu restok</small>
                </div>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="table table-hover restok-table">
                <thead style="background:#f8f9fa; font-weight:600;">
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Kecepatan Jual</th>
                        <th class="text-center">Trend</th>
                        <th class="text-center">Prioritas</th>
                        <th class="text-center">Rekomendasi</th>
                        <th class="text-center">Estimasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekomendasi as $index => $item)
                    <tr style="border-bottom:1px solid #dee2e6;">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="restok-product">
                                @php($imageUrl = \App\Helpers\ImageHelper::getImageUrl($item['produk']->gambar))
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $item['produk']->nama_produk }}">
                                @else
                                    <div style="width:44px;height:44px;border-radius:8px;background:#f1f3f5;border:1px solid #e9ecef;display:flex;align-items:center;justify-content:center;color:#adb5bd;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $item['produk']->nama_produk }}</strong>
                                    <div class="restok-meta">{{ $item['produk']->kode_produk }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($item['metrik']['stok_persentase'] < 25)
                                <span class="badge badge-danger restok-chip" style="background-color:#dc3545;color:#fff;">Stok: {{ $item['produk']->stok }} ml</span>
                            @elseif($item['metrik']['stok_persentase'] < 50)
                                <span class="badge badge-warning restok-chip" style="background-color:#ffc107;color:#212529;">Stok: {{ $item['produk']->stok }} ml</span>
                            @else
                                <span class="badge badge-success restok-chip" style="background-color:#198754;color:#fff;">Stok: {{ $item['produk']->stok }} ml</span>
                            @endif
                            <div class="metric-label">{{ round($item['metrik']['stok_persentase']) }}%</div>
                        </td>
                        <td class="text-center">
                            <div><strong>{{ $item['metrik']['kecepatan_jual'] }}</strong> ml/hari</div>
                            <div class="metric-label">{{ $item['metrik']['total_penjualan_30_hari'] }} ml (30 hari)</div>
                        </td>
                        <td class="text-center">
                            @if($item['metrik']['trend_penjualan'] > 5)
                                <span class="badge badge-success restok-chip" style="background-color:#198754;color:#fff;"><i class="fas fa-arrow-up"></i> Trend naik +{{ round($item['metrik']['trend_penjualan']) }}%</span>
                            @elseif($item['metrik']['trend_penjualan'] < -5)
                                <span class="badge badge-danger restok-chip" style="background-color:#dc3545;color:#fff;"><i class="fas fa-arrow-down"></i> Trend turun {{ round($item['metrik']['trend_penjualan']) }}%</span>
                            @else
                                <span class="badge badge-secondary restok-chip" style="background-color:#adb5bd;color:#212529;"><i class="fas fa-minus"></i> Trend stabil</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php($prioritas = $item['fuzzy']['prioritas_label'] ?? 'Rendah')
                            @php($badgeMap = [
                                'Sangat Tinggi' => 'badge-danger',
                                'Tinggi' => 'badge-warning',
                                'Sedang' => 'badge-info',
                                'Rendah' => 'badge-secondary',
                                'Sangat Rendah' => 'badge-success'
                            ])
                            @php($badgeClass = $badgeMap[$prioritas] ?? 'badge-secondary')
                            @if($badgeClass == 'badge-danger')
                                <span class="badge badge-danger restok-chip" style="background-color:#dc3545;color:#fff;">Prioritas: {{ $prioritas }}</span>
                            @elseif($badgeClass == 'badge-warning')
                                <span class="badge badge-warning restok-chip" style="background-color:#ffc107;color:#212529;">Prioritas: {{ $prioritas }}</span>
                            @elseif($badgeClass == 'badge-info')
                                <span class="badge badge-info restok-chip" style="background-color:#0dcaf0;color:#212529;">Prioritas: {{ $prioritas }}</span>
                            @elseif($badgeClass == 'badge-success')
                                <span class="badge badge-success restok-chip" style="background-color:#198754;color:#fff;">Prioritas: {{ $prioritas }}</span>
                            @else
                                <span class="badge badge-secondary restok-chip" style="background-color:#adb5bd;color:#212529;">Prioritas: {{ $prioritas }}</span>
                            @endif
                            <div class="metric-label">Score: {{ $item['fuzzy']['prioritas_score'] }}</div>
                        </td>
                        <td class="text-center" style="max-width: 220px;">
                            <strong class="text-primary">{{ number_format($item['fuzzy']['jumlah_restok']) }} ml</strong>
                            <div class="metric-label">{{ $item['fuzzy']['alasan'] }}</div>
                        </td>
                        <td class="text-center">
                            @if($item['fuzzy']['waktu_restok_hari'] <= 7)
                                <span class="badge badge-danger restok-chip" style="background-color:#dc3545;color:#fff;"><i class="fas fa-exclamation-triangle"></i> Estimasi habis: {{ $item['fuzzy']['waktu_restok_hari'] }} hari</span>
                            @elseif($item['fuzzy']['waktu_restok_hari'] <= 14)
                                <span class="badge badge-warning restok-chip" style="background-color:#ffc107;color:#212529;"><i class="fas fa-clock"></i> Estimasi habis: {{ $item['fuzzy']['waktu_restok_hari'] }} hari</span>
                            @else
                                <span class="badge badge-success restok-chip" style="background-color:#198754;color:#fff;"><i class="fas fa-check"></i> Estimasi habis: {{ min($item['fuzzy']['waktu_restok_hari'], 90) }}+ hari</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="restok-actions">
                                <a class="btn btn-sm btn-primary" href="{{ route('produk.create') }}">
                                    <i class="fas fa-plus-circle me-1"></i>Tambah Produk
                                </a>
                                <button class="btn btn-sm btn-outline-secondary" data-detail='@json($item)' onclick="window.handleDetailClick(this)">
                                    <i class="fas fa-info-circle me-1"></i>Detail
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-box-open" style="font-size: 2.5rem;"></i>
                            <div class="mt-2">Tidak ada data produk parfum</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Modal Restok -->
<div class="modal fade" id="restokModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('restok.proses') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Proses Restok
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="produk_id" id="modal_produk_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="modal_produk_nama" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah Restok (ml)</label>
                        <input type="number" class="form-control" name="jumlah" id="modal_jumlah" required min="1">
                        <small class="text-muted">Rekomendasi sistem: <span id="modal_rekomendasi"></span> ml</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Proses Restok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>Detail Analisis Fuzzy Logic
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be filled by JavaScript -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showRestokModal(produkId, produkNama, jumlahRekomendasi) {
    document.getElementById('modal_produk_id').value = produkId;
    document.getElementById('modal_produk_nama').value = produkNama;
    document.getElementById('modal_jumlah').value = jumlahRekomendasi;
    document.getElementById('modal_rekomendasi').textContent = new Intl.NumberFormat('id-ID').format(jumlahRekomendasi);
    const modal = new bootstrap.Modal(document.getElementById('restokModal'));
    modal.show();
}

window.handleDetailClick = function(btn) {
    try {
        const data = JSON.parse(btn.getAttribute('data-detail'));
        window.showDetailModal(data);
    } catch (e) {
        alert('Gagal menampilkan detail. Data tidak valid.');
        console.error(e);
    }
};
window.showDetailModal = function(data) {
    const content = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3"><i class="fas fa-database me-2"></i>Data Historis</h6>
                <table class="table table-sm">
                    <tr>
                        <td>Stok Saat Ini</td>
                        <td><strong>${data.metrik.stok_saat_ini} ml</strong></td>
                    </tr>
                    <tr>
                        <td>Stok Maksimal</td>
                        <td>${data.metrik.stok_maksimal} ml</td>
                    </tr>
                    <tr>
                        <td>Persentase Stok</td>
                        <td>${Number(data.metrik.stok_persentase).toFixed(2)}%</td>
                    </tr>
                    <tr>
                        <td>Total Penjualan (30 hari)</td>
                        <td>${data.metrik.total_penjualan_30_hari} ml</td>
                    </tr>
                    <tr>
                        <td>Penjualan 15 Hari Pertama</td>
                        <td>${data.metrik.penjualan_15_hari_pertama} ml</td>
                    </tr>
                    <tr>
                        <td>Penjualan 15 Hari Terakhir</td>
                        <td>${data.metrik.penjualan_15_hari_terakhir} ml</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3"><i class="fas fa-brain me-2"></i>Hasil Fuzzy Logic</h6>
                <table class="table table-sm">
                    <tr>
                        <td>Prioritas Score</td>
                        <td><strong>${data.fuzzy.prioritas_score}</strong></td>
                    </tr>
                    <tr>
                        <td>Label Prioritas</td>
                        <td>
                            ${(() => {
                                let color = '#adb5bd', text = '#212529';
                                const label = data.fuzzy.prioritas_label;
                                if(label === 'Sangat Tinggi') { color = '#dc3545'; text = '#fff'; }
                                else if(label === 'Tinggi') { color = '#ffc107'; text = '#212529'; }
                                else if(label === 'Sedang') { color = '#0dcaf0'; text = '#212529'; }
                                else if(label === 'Sangat Rendah') { color = '#198754'; text = '#fff'; }
                                return `<span class='badge restok-chip' style='background-color:${color};color:${text};'>${label}</span> <span style='color:${text};margin-left:8px;'>Prioritas hasil analisis fuzzy logic</span>`;
                            })()}
                        </td>
                    </tr>
                    <tr>
                        <td>Jumlah Restok</td>
                        <td><strong class="text-primary">${new Intl.NumberFormat('id-ID').format(data.fuzzy.jumlah_restok)} ml</strong></td>
                    </tr>
                    <tr>
                        <td>Estimasi Habis</td>
                        <td>${data.fuzzy.waktu_restok_hari} hari</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>Alasan:</strong><br>
                            <small>${data.fuzzy.alasan}</small>
                        </td>
                    </tr>
                </table>
                <div class="alert alert-info mt-3">
                    <small>
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Fuzzy Logic</strong> menganalisis 3 parameter: 
                        Stok (${Number(data.metrik.stok_persentase).toFixed(1)}%), 
                        Kecepatan Jual (${data.metrik.kecepatan_jual} ml/hari), 
                        Trend (${Number(data.metrik.trend_penjualan).toFixed(1)}%)
                    </small>
                </div>
            </div>
        </div>
    `;
    document.getElementById('detailContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
};
</script>
@endpush
