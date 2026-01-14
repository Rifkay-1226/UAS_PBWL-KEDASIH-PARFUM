<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Kedasih',
            'email' => 'admin@kedasih.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Pegawai User
        User::create([
            'name' => 'Pegawai Toko',
            'email' => 'pegawai@kedasih.com',
            'password' => Hash::make('password123'),
            'role' => 'pegawai'
        ]);

        // Kategori
        $kategoris = [
            ['nama_kategori' => 'Parfum Pria', 'deskripsi' => 'Parfum untuk pria'],
            ['nama_kategori' => 'Parfum Wanita', 'deskripsi' => 'Parfum untuk wanita'],
            ['nama_kategori' => 'Body Mist', 'deskripsi' => 'Body mist dan body spray'],
            ['nama_kategori' => 'Roll On', 'deskripsi' => 'Parfum roll on'],
            ['nama_kategori' => 'Miniature', 'deskripsi' => 'Parfum ukuran kecil'],
        ];

        // Produk Sample
        $products = [
            [
                'kategori_id' => 1,
                'kode_produk' => 'PRD-MALE001',
                'nama_produk' => 'Sauvage Eau de Toilette',
                'deskripsi' => 'Parfum pria dengan aroma segar',
                'harga' => 350000,
                'stok' => 50
            ],
            [
                'kategori_id' => 2,
                'kode_produk' => 'PRD-FEM001',
                'nama_produk' => 'Chanel No. 5',
                'deskripsi' => 'Parfum wanita klasik',
                'harga' => 450000,
                'stok' => 30
            ],
            [
                'kategori_id' => 3,
                'kode_produk' => 'PRD-BM001',
                'nama_produk' => 'Victoria Secret Body Mist',
                'deskripsi' => 'Body mist aroma buah',
                'harga' => 150000,
                'stok' => 80
            ],
        ];

        foreach ($products as $product) {
            Produk::create($product);
        }
    }
}
