<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with(['user', 'produk.kategori', 'details.produk.kategori'])
            ->latest()
            ->paginate(20);
        
        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['user', 'produk.kategori', 'details.produk.kategori']);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function create()
    {
        $produks = Produk::where('stok', '>', 0)->with('kategori')->get();
        $produkList = $produks->map(function($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama_produk,
                'harga' => $p->harga,
                'kategori' => strtolower($p->kategori->nama_kategori),
            ];
        });
        return view('transaksi.create', compact('produks', 'produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $no_transaksi = 'TRX-' . date('Ymd') . '-' . Str::upper(Str::random(6));

            // Validasi stok untuk semua produk
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                if ($produk->stok < $item['jumlah']) {
                    DB::rollBack();
                    return back()->with('error', "Stok {$produk->nama_produk} tidak mencukupi!");
                }
                $total += $produk->harga * $item['jumlah'];
            }

            // Buat transaksi (produk_id diisi dengan produk pertama untuk backward compatibility)
            $firstItem = $request->items[0];
            $firstProduk = Produk::find($firstItem['produk_id']);
            
            $transaksi = Transaksi::create([
                'no_transaksi' => $no_transaksi,
                'kode_transaksi' => $no_transaksi,
                'user_id' => auth()->id(),
                'produk_id' => $firstProduk->id,
                'harga_satuan' => $firstProduk->harga,
                'jumlah' => $firstItem['jumlah'],
                'total' => $total,
                'quantity' => $firstItem['jumlah'],
                'total_harga' => $total,
                'status' => 'completed',
                'tanggal_transaksi' => Carbon::now(),
            ]);

            // Buat detail untuk setiap produk
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $subtotal = $produk->harga * $item['jumlah'];

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk->id,
                    'harga_satuan' => $produk->harga,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ]);

                // Update stok produk
                $produk->decrement('stok', $item['jumlah']);
            }

            DB::commit();
            return redirect()->route('transaksi.my')
                ->with('success', 'Transaksi berhasil dibuat!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transaksi gagal: ' . $e->getMessage());
        }
    }

    public function destroy(Transaksi $transaksi)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok dari detail transaksi (jika ada)
            if ($transaksi->details()->exists()) {
                foreach ($transaksi->details as $detail) {
                    $detail->produk->increment('stok', $detail->jumlah);
                }
            } else {
                // Kembalikan stok dari transaksi legacy (single product)
                $transaksi->produk->increment('stok', $transaksi->jumlah);
            }

            $transaksi->delete();
            
            DB::commit();
            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function myTransactions()
    {
        $transaksi = Transaksi::with(['produk.kategori', 'details.produk.kategori'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('transaksi.my', compact('transaksi'));
    }

    public function laporan(Request $request)
    {
        $query = Transaksi::with(['user', 'produk.kategori']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transaksi = $query->latest()->get();

        return view('admin.laporan', compact('transaksi'));
    }

    public function export(Request $request)
    {
        // Implementasi export ke Excel/PDF bisa ditambahkan nanti
        return back()->with('info', 'Fitur export sedang dalam pengembangan');
    }
}