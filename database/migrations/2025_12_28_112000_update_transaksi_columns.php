<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('transaksi')) {
            return;
        }

        Schema::table('transaksi', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi', 'no_transaksi')) {
                $table->string('no_transaksi', 50)->nullable()->unique()->after('id');
            }

            if (!Schema::hasColumn('transaksi', 'harga_satuan')) {
                $table->decimal('harga_satuan', 12, 2)->default(0)->after('produk_id');
            }

            if (!Schema::hasColumn('transaksi', 'jumlah')) {
                $table->integer('jumlah')->default(0)->after('harga_satuan');
            }

            if (!Schema::hasColumn('transaksi', 'total')) {
                $table->decimal('total', 12, 2)->default(0)->after('jumlah');
            }
        });
    }

    public function down(): void
    {
        // No down migration to avoid issues when Doctrine DBAL is absent.
    }
};
