<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $data = [];
        
        if (auth()->user()->isAdmin()) {
            $data['total_produk'] = Produk::count();
            $data['total_transaksi'] = Transaksi::count();
            $data['total_users'] = User::count();
            $data['pendapatan_hari_ini'] = Transaksi::whereDate('created_at', today())
                ->sum('total_harga');
            $data['transaksi_terbaru'] = Transaksi::with(['user', 'produk'])
                ->latest()
                ->take(5)
                ->get();
            $data['produk_stok_rendah'] = 0; // Tambahkan default agar tidak error di view
        } else {
            $data['transaksi_hari_ini'] = Transaksi::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->count();
            $data['produk_stok_rendah'] = Produk::where('stok', '<', 10)->count();
        }
        
        return view('dashboard', $data);
    }
}