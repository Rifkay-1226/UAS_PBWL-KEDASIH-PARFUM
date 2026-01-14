<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Services\FuzzyLogicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RestokController extends Controller
{
    protected $fuzzyService;

    public function __construct(FuzzyLogicService $fuzzyService)
    {
        $this->fuzzyService = $fuzzyService;
    }

    /**
     * Tampilkan halaman rekomendasi restok
     */
    public function index()
    {
        // Hanya admin yang bisa akses
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua produk parfum dengan relasi
        $produkParfum = Produk::with('kategori')
            ->whereHas('kategori', function($query) {
                $query->whereRaw('LOWER(nama_kategori) = ?', ['parfum']);
            })
            ->get();

        $rekomendasi = [];

        foreach ($produkParfum as $produk) {
            // Hitung metrik untuk fuzzy logic
            $metrik = $this->hitungMetrikProduk($produk);
            
            // Hitung rekomendasi menggunakan Fuzzy Logic
            $fuzzyResult = $this->fuzzyService->hitungRekomendasiRestok(
                $metrik['stok_persentase'],
                $metrik['kecepatan_jual'],
                $metrik['trend_penjualan']
            );

            $rekomendasi[] = [
                'produk' => $produk,
                'metrik' => $metrik,
                'fuzzy' => $fuzzyResult
            ];
        }

        // Urutkan berdasarkan prioritas score (tertinggi dulu)
        usort($rekomendasi, function($a, $b) {
            return $b['fuzzy']['prioritas_score'] <=> $a['fuzzy']['prioritas_score'];
        });

        return view('admin.restok.index', compact('rekomendasi'));
    }

    /**
     * Hitung metrik produk untuk input fuzzy logic
     */
    private function hitungMetrikProduk($produk)
    {
        // Ambil data transaksi 30 hari terakhir
        $startDate = Carbon::now()->subDays(30);
        $midDate = Carbon::now()->subDays(15);
        
        // Hitung total penjualan 30 hari terakhir
        $totalPenjualan30Hari = $this->getTotalPenjualan($produk->id, $startDate, now());
        
        // Hitung total penjualan 15 hari pertama vs 15 hari terakhir (untuk trend)
        $penjualan15HariPertama = $this->getTotalPenjualan($produk->id, $startDate, $midDate);
        $penjualan15HariTerakhir = $this->getTotalPenjualan($produk->id, $midDate, now());

        // Kecepatan jual (rata-rata per hari dalam 30 hari)
        $kecepatanJual = $totalPenjualan30Hari / 30;

        // Trend penjualan (persentase perubahan)
        if ($penjualan15HariPertama > 0) {
            $trendPenjualan = (($penjualan15HariTerakhir - $penjualan15HariPertama) / $penjualan15HariPertama) * 100;
        } else {
            $trendPenjualan = $penjualan15HariTerakhir > 0 ? 100 : 0;
        }

        // Asumsi stok maksimal (bisa disesuaikan)
        $stokMaksimal = max($produk->stok * 2, 500); // Minimal 500ml
        $stokPersentase = ($produk->stok / $stokMaksimal) * 100;

        return [
            'stok_saat_ini' => $produk->stok,
            'stok_maksimal' => $stokMaksimal,
            'stok_persentase' => min(100, $stokPersentase),
            'kecepatan_jual' => round($kecepatanJual, 2),
            'trend_penjualan' => round($trendPenjualan, 2),
            'total_penjualan_30_hari' => $totalPenjualan30Hari,
            'penjualan_15_hari_pertama' => $penjualan15HariPertama,
            'penjualan_15_hari_terakhir' => $penjualan15HariTerakhir
        ];
    }

    /**
     * Get total penjualan produk dalam periode tertentu
     */
    private function getTotalPenjualan($produkId, $startDate, $endDate)
    {
        // Cek dari transaksi_detail (multi-product)
        $fromDetails = TransaksiDetail::where('produk_id', $produkId)
            ->whereHas('transaksi', function($query) use ($startDate, $endDate) {
                // gunakan created_at karena kolom tanggal tidak ada di tabel
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('jumlah');

        // Cek dari transaksi langsung (legacy single-product)
        $fromTransaksi = Transaksi::where('produk_id', $produkId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereDoesntHave('details') // hanya yang tidak punya detail
            ->sum('jumlah');

        return $fromDetails + $fromTransaksi;
    }

    /**
     * Proses restok otomatis
     */
    public function prosesRestok(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $produk = Produk::findOrFail($request->produk_id);
            $produk->stok += $request->jumlah;
            $produk->save();

            // Log restok (bisa dibuat tabel log_restok jika diperlukan)
            
            DB::commit();

            return redirect()->route('restok.index')
                ->with('success', "Berhasil restok {$produk->nama_produk} sebanyak {$request->jumlah} ml");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal melakukan restok: ' . $e->getMessage());
        }
    }

    /**
     * Export rekomendasi ke Excel/PDF (opsional untuk future development)
     */
    public function export(Request $request)
    {
        // TODO: Implementasi export
    }
}
