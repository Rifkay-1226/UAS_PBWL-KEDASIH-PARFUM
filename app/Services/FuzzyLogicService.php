<?php

namespace App\Services;

class FuzzyLogicService
{
    /**
     * Hitung rekomendasi restok menggunakan Fuzzy Logic
     * 
     * @param float $stokPersentase - Persentase stok tersisa (0-100)
     * @param float $kecepatanJual - Rata-rata penjualan per hari
     * @param float $trendPenjualan - Trend penjualan (perubahan % dalam 7 hari terakhir)
     * @return array
     */
    public function hitungRekomendasiRestok($stokPersentase, $kecepatanJual, $trendPenjualan)
    {
        // Fuzzifikasi - Ubah nilai crisp menjadi nilai fuzzy
        $stokFuzzy = $this->fuzzifikasiStok($stokPersentase);
        $kecepatanFuzzy = $this->fuzzifikasiKecepatan($kecepatanJual);
        $trendFuzzy = $this->fuzzifikasiTrend($trendPenjualan);

        // Inference - Terapkan aturan fuzzy
        $prioritasRules = $this->terapkanAturanFuzzy($stokFuzzy, $kecepatanFuzzy, $trendFuzzy);

        // Defuzzifikasi - Ubah nilai fuzzy menjadi nilai crisp
        $prioritasScore = $this->defuzzifikasi($prioritasRules);
        $prioritasLabel = $this->tentukanPrioritas($prioritasScore);
        $jumlahRestok = $this->hitungJumlahRestok($kecepatanJual, $prioritasScore);

        return [
            'prioritas_score' => round($prioritasScore, 2),
            'prioritas_label' => $prioritasLabel,
            'jumlah_restok' => $jumlahRestok,
            'alasan' => $this->generateAlasan($stokFuzzy, $kecepatanFuzzy, $trendFuzzy),
            'waktu_restok_hari' => $this->estimasiWaktuRestok($stokPersentase, $kecepatanJual)
        ];
    }

    /**
     * Fuzzifikasi Stok (Rendah, Sedang, Tinggi)
     */
    private function fuzzifikasiStok($stokPersentase)
    {
        return [
            'rendah' => $this->trimf($stokPersentase, 0, 0, 25),
            'sedang' => $this->trimf($stokPersentase, 15, 35, 55),
            'tinggi' => $this->trimf($stokPersentase, 45, 100, 100)
        ];
    }

    /**
     * Fuzzifikasi Kecepatan Jual (Lambat, Normal, Cepat)
     */
    private function fuzzifikasiKecepatan($kecepatan)
    {
        return [
            'lambat' => $this->trimf($kecepatan, 0, 0, 5),
            'normal' => $this->trimf($kecepatan, 3, 8, 13),
            'cepat' => $this->trimf($kecepatan, 10, 50, 50)
        ];
    }

    /**
     * Fuzzifikasi Trend (Turun, Stabil, Naik)
     */
    private function fuzzifikasiTrend($trend)
    {
        return [
            'turun' => $this->trimf($trend, -100, -100, -5),
            'stabil' => $this->trimf($trend, -10, 0, 10),
            'naik' => $this->trimf($trend, 5, 100, 100)
        ];
    }

    /**
     * Fungsi Keanggotaan Triangular
     */
    private function trimf($x, $a, $b, $c)
    {
        if ($x <= $a || $x >= $c) {
            return 0;
        } elseif ($x == $b) {
            return 1;
        } elseif ($x > $a && $x < $b) {
            return ($x - $a) / ($b - $a);
        } else {
            return ($c - $x) / ($c - $b);
        }
    }

    /**
     * Terapkan Aturan Fuzzy (IF-THEN Rules)
     */
    private function terapkanAturanFuzzy($stok, $kecepatan, $trend)
    {
        $rules = [];

        // Aturan untuk prioritas SANGAT TINGGI (90-100)
        $rules[] = min($stok['rendah'], $kecepatan['cepat'], $trend['naik']) * 100;
        $rules[] = min($stok['rendah'], $kecepatan['cepat'], $trend['stabil']) * 95;
        $rules[] = min($stok['rendah'], $kecepatan['normal'], $trend['naik']) * 90;

        // Aturan untuk prioritas TINGGI (70-89)
        $rules[] = min($stok['rendah'], $kecepatan['normal'], $trend['stabil']) * 85;
        $rules[] = min($stok['rendah'], $kecepatan['lambat'], $trend['naik']) * 80;
        $rules[] = min($stok['sedang'], $kecepatan['cepat'], $trend['naik']) * 85;
        $rules[] = min($stok['sedang'], $kecepatan['cepat'], $trend['stabil']) * 80;
        $rules[] = min($stok['rendah'], $kecepatan['cepat'], $trend['turun']) * 75;

        // Aturan untuk prioritas SEDANG (40-69)
        $rules[] = min($stok['sedang'], $kecepatan['normal'], $trend['naik']) * 65;
        $rules[] = min($stok['sedang'], $kecepatan['normal'], $trend['stabil']) * 60;
        $rules[] = min($stok['rendah'], $kecepatan['lambat'], $trend['stabil']) * 55;
        $rules[] = min($stok['sedang'], $kecepatan['lambat'], $trend['naik']) * 50;
        $rules[] = min($stok['tinggi'], $kecepatan['cepat'], $trend['naik']) * 60;

        // Aturan untuk prioritas RENDAH (20-39)
        $rules[] = min($stok['tinggi'], $kecepatan['normal'], $trend['stabil']) * 35;
        $rules[] = min($stok['sedang'], $kecepatan['lambat'], $trend['stabil']) * 30;
        $rules[] = min($stok['tinggi'], $kecepatan['lambat'], $trend['stabil']) * 25;

        // Aturan untuk prioritas SANGAT RENDAH (0-19)
        $rules[] = min($stok['tinggi'], $kecepatan['lambat'], $trend['turun']) * 15;
        $rules[] = min($stok['tinggi'], $kecepatan['normal'], $trend['turun']) * 20;
        $rules[] = min($stok['sedang'], $kecepatan['lambat'], $trend['turun']) * 10;

        return $rules;
    }

    /**
     * Defuzzifikasi menggunakan metode Weighted Average
     */
    private function defuzzifikasi($rules)
    {
        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($rules as $value) {
            if ($value > 0) {
                $weightedSum += $value;
                $totalWeight += 1;
            }
        }

        return $totalWeight > 0 ? $weightedSum / $totalWeight : 0;
    }

    /**
     * Tentukan label prioritas
     */
    private function tentukanPrioritas($score)
    {
        if ($score >= 85) return 'Sangat Tinggi';
        if ($score >= 70) return 'Tinggi';
        if ($score >= 40) return 'Sedang';
        if ($score >= 20) return 'Rendah';
        return 'Sangat Rendah';
    }

    /**
     * Hitung jumlah restok yang disarankan
     */
    private function hitungJumlahRestok($kecepatanJual, $prioritasScore)
    {
        // Basis: 30 hari supply
        $baseSupply = $kecepatanJual * 30;
        
        // Faktor pengali berdasarkan prioritas
        $multiplier = 1 + ($prioritasScore / 100);
        
        // Safety stock 20%
        $safetyStock = $baseSupply * 0.2;
        
        $jumlah = ($baseSupply * $multiplier) + $safetyStock;
        
        // Minimal 50ml untuk parfum
        return max(50, ceil($jumlah / 10) * 10);
    }

    /**
     * Generate alasan rekomendasi
     */
    private function generateAlasan($stok, $kecepatan, $trend)
    {
        $alasan = [];

        // Analisis stok
        if ($stok['rendah'] > 0.5) {
            $alasan[] = 'Stok tersisa sangat sedikit';
        } elseif ($stok['sedang'] > 0.5) {
            $alasan[] = 'Stok dalam kondisi sedang';
        } else {
            $alasan[] = 'Stok masih cukup';
        }

        // Analisis kecepatan jual
        if ($kecepatan['cepat'] > 0.5) {
            $alasan[] = 'Penjualan sangat cepat';
        } elseif ($kecepatan['normal'] > 0.5) {
            $alasan[] = 'Penjualan stabil';
        } else {
            $alasan[] = 'Penjualan lambat';
        }

        // Analisis trend
        if ($trend['naik'] > 0.5) {
            $alasan[] = 'Trend penjualan meningkat';
        } elseif ($trend['stabil'] > 0.5) {
            $alasan[] = 'Trend penjualan stabil';
        } else {
            $alasan[] = 'Trend penjualan menurun';
        }

        return implode(', ', $alasan);
    }

    /**
     * Estimasi waktu hingga stok habis
     */
    private function estimasiWaktuRestok($stokPersentase, $kecepatanJual)
    {
        if ($kecepatanJual <= 0) {
            return 999; // Stok aman sangat lama
        }

        // Asumsi: jika stokPersentase = 30% dan kecepatan 3/hari, maka estimasi
        // Konversi persentase ke hari tersisa
        $hariTersisa = ($stokPersentase / 100) * (100 / $kecepatanJual);
        
        return max(0, round($hariTersisa));
    }
}
