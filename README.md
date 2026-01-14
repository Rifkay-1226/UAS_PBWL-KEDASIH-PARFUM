<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Kedasih Parfum

Aplikasi Kedasih Parfum adalah project UAS untuk matakuliah Pemrograman Berbasis Website Lanjutan. Aplikasi ini dibangun menggunakan Laravel 12 dan menerapkan konsep manajemen data produk, kategori, transaksi, serta fitur autentikasi pengguna.

## Fitur Utama
- Manajemen produk parfum (CRUD)
- Manajemen kategori produk
- Transaksi penjualan dan detail transaksi
- Autentikasi dan manajemen user (admin & user)
- Dashboard dan laporan penjualan
- Implementasi logika fuzzy untuk rekomendasi/analisis (opsional)
- Desain responsif dengan Tailwind CSS & Bootstrap

## Teknologi
- Laravel 12 (PHP 8.2)
- MySQL
- Tailwind CSS & Bootstrap 5
- Vite

## Struktur Project
- Backend: Laravel (app, routes, database, config)
- Frontend: Blade template, Tailwind CSS, Bootstrap
- Database: migrasi, seeder, dan relasi antar tabel

## Cara Menjalankan
1. Clone repository ini
2. Jalankan `composer install` & `npm install`
3. Copy `.env.example` ke `.env` dan sesuaikan konfigurasi database
4. Jalankan migrasi dan seeder: `php artisan migrate --seed`
5. Jalankan server: `php artisan serve` dan `npm run dev`