<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'no_transaksi',
        'kode_transaksi',
        'user_id',
        'produk_id',
        'harga_satuan',
        'jumlah',
        'total',
        'quantity',
        'total_harga',
        'status',
        'tanggal_transaksi',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}