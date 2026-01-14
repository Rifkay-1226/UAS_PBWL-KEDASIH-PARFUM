<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index() {
        $products = Produk::with('kategori')->latest()->paginate(20);
        return view('admin.produk.index', compact('products'));
    }

    public function create() {
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $kode_produk = 'PRD-' . date('Ymd') . '-' . Str::upper(Str::random(6));

        $produk = new Produk($request->except('gambar'));
        $produk->kode_produk = $kode_produk;

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk', 'public');
            $produk->gambar = $path;
        }

        $produk->save();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Produk $produk) {
        $produk->load('kategori', 'transaksi');
        return view('admin.produk.show', compact('produk'));
    }

    public function edit(Produk $produk) {
        $kategoris = Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk) {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $produk->fill($request->except('gambar'));

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                \Storage::disk('public')->delete($produk->gambar);
            }
            $path = $request->file('gambar')->store('produk', 'public');
            $produk->gambar = $path;
        }

        $produk->save();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Produk $produk) {
        if ($produk->gambar) {
            \Storage::disk('public')->delete($produk->gambar);
        }
        
        $produk->delete();
        
        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    // Untuk Pegawai
    public function listForPegawai() {
        $products = Produk::with('kategori')->where('stok', '>', 0)->paginate(20);
        return view('produk.list', ['produk' => $products]);
    }
}