<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BatchBarang;
use App\Models\AkunKeuangan;
use App\Models\JurnalEntry;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Process incoming goods
     */
    public function prosesBarangMasuk(array $data): BarangMasuk
    {
        return DB::transaction(function () use ($data) {
            $barang = Barang::findOrFail($data['barang_id']);
            $jumlah = $data['jumlah'];
            $hargaSatuan = $data['harga_satuan'];
            $totalHarga = $jumlah * $hargaSatuan;

            // Create transaction record
            $masuk = BarangMasuk::create([
                'kode_transaksi' => BarangMasuk::generateKode(),
                'barang_id' => $barang->id,
                'user_id' => auth()->id(),
                'tanggal' => $data['tanggal'],
                'jumlah' => $jumlah,
                'harga_satuan' => $hargaSatuan,
                'total_harga' => $totalHarga,
                'supplier' => $data['supplier'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Create batch for FIFO tracking
            BatchBarang::create([
                'barang_id' => $barang->id,
                'barang_masuk_id' => $masuk->id,
                'jumlah_awal' => $jumlah,
                'jumlah_sisa' => $jumlah,
                'harga_satuan' => $hargaSatuan,
                'tanggal_masuk' => $data['tanggal'],
            ]);

            // Update stock using Average method
            $totalNilaiBaru = $barang->total_nilai + $totalHarga;
            $stokBaru = $barang->stok + $jumlah;
            $hargaRataRata = $stokBaru > 0 ? $totalNilaiBaru / $stokBaru : 0;

            $barang->update([
                'stok' => $stokBaru,
                'harga_rata_rata' => $hargaRataRata,
                'total_nilai' => $totalNilaiBaru,
            ]);

            // Create journal entries (Debit: Persediaan, Kredit: Kas/Hutang)
            $this->catatJurnalMasuk($masuk, $totalHarga);

            return $masuk;
        });
    }

    /**
     * Process outgoing goods
     */
    public function prosesBarangKeluar(array $data): BarangKeluar
    {
        return DB::transaction(function () use ($data) {
            $barang = Barang::findOrFail($data['barang_id']);
            $jumlah = $data['jumlah'];

            if ($barang->stok < $jumlah) {
                throw new \Exception("Stok tidak mencukupi! Stok tersedia: {$barang->stok}");
            }

            // Calculate price based on stock method
            if ($barang->metode_stok === 'fifo') {
                $result = $this->hitungFIFO($barang, $jumlah);
                $hargaSatuan = $result['total_nilai'] / $jumlah;
                $totalHarga = $result['total_nilai'];
            } else {
                $hargaSatuan = $barang->harga_rata_rata;
                $totalHarga = $jumlah * $hargaSatuan;
            }

            $hargaJualSatuan = isset($data['harga_jual_satuan']) && $data['harga_jual_satuan'] > 0 ? $data['harga_jual_satuan'] : null;
            $totalJual = $hargaJualSatuan ? ($hargaJualSatuan * $jumlah) : null;

            // Create transaction record
            $keluar = BarangKeluar::create([
                'kode_transaksi' => BarangKeluar::generateKode(),
                'barang_id' => $barang->id,
                'user_id' => auth()->id(),
                'tanggal' => $data['tanggal'],
                'jumlah' => $jumlah,
                'harga_satuan' => round($hargaSatuan, 2),
                'total_harga' => round($totalHarga, 2),
                'harga_jual_satuan' => $hargaJualSatuan,
                'total_jual' => $totalJual,
                'tujuan' => $data['tujuan'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Update stock
            $stokBaru = $barang->stok - $jumlah;
            $totalNilaiBaru = $barang->total_nilai - $totalHarga;

            if ($stokBaru > 0) {
                $hargaRataRataBaru = $totalNilaiBaru / $stokBaru;
            } else {
                $hargaRataRataBaru = 0;
                $totalNilaiBaru = 0;
            }

            $barang->update([
                'stok' => $stokBaru,
                'harga_rata_rata' => round($hargaRataRataBaru, 2),
                'total_nilai' => round($totalNilaiBaru, 2),
            ]);

            // Create journal entries (Debit: HPP/Beban, Kredit: Persediaan)
            $this->catatJurnalKeluar($keluar, round($totalHarga, 2));

            // Create journal entries for Sales if it's a sale
            if ($totalJual && $totalJual > 0) {
                $this->catatJurnalPenjualan($keluar, $totalJual);
            }

            return $keluar;
        });
    }

    /**
     * Calculate FIFO - deducts from oldest batches first
     */
    private function hitungFIFO(Barang $barang, int $jumlah): array
    {
        $batches = $barang->batches()->get();
        $sisaKebutuhan = $jumlah;
        $totalNilai = 0;
        $detail = [];

        foreach ($batches as $batch) {
            if ($sisaKebutuhan <= 0) break;

            $ambil = min($sisaKebutuhan, $batch->jumlah_sisa);
            $nilai = $ambil * $batch->harga_satuan;

            $batch->update(['jumlah_sisa' => $batch->jumlah_sisa - $ambil]);

            $detail[] = [
                'batch_id' => $batch->id,
                'jumlah' => $ambil,
                'harga_satuan' => $batch->harga_satuan,
                'nilai' => $nilai,
            ];

            $totalNilai += $nilai;
            $sisaKebutuhan -= $ambil;
        }

        return [
            'total_nilai' => $totalNilai,
            'detail' => $detail,
        ];
    }

    /**
     * Record journal entry for incoming goods
     */
    private function catatJurnalMasuk(BarangMasuk $masuk, float $total): void
    {
        $kodeJurnal = JurnalEntry::generateKode();

        // Debit: Persediaan (Asset account)
        $akunPersediaan = AkunKeuangan::where('kode', '1-100')->first();
        // Kredit: Kas (Asset account - decrease)
        $akunKas = AkunKeuangan::where('kode', '1-000')->first();

        if ($akunPersediaan && $akunKas) {
            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnal,
                'tanggal' => $masuk->tanggal,
                'akun_id' => $akunPersediaan->id,
                'debit' => $total,
                'kredit' => 0,
                'keterangan' => "Pembelian barang: {$masuk->barang->nama}",
                'referensi_tipe' => 'barang_masuk',
                'referensi_id' => $masuk->id,
                'user_id' => auth()->id(),
            ]);

            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnal,
                'tanggal' => $masuk->tanggal,
                'akun_id' => $akunKas->id,
                'debit' => 0,
                'kredit' => $total,
                'keterangan' => "Pembelian barang: {$masuk->barang->nama}",
                'referensi_tipe' => 'barang_masuk',
                'referensi_id' => $masuk->id,
                'user_id' => auth()->id(),
            ]);

            // Update account balances
            $akunPersediaan->increment('saldo', $total);
            $akunKas->decrement('saldo', $total);
        }
    }

    /**
     * Record journal entry for outgoing goods
     */
    private function catatJurnalKeluar(BarangKeluar $keluar, float $total): void
    {
        $kodeJurnal = JurnalEntry::generateKode();

        // Debit: HPP / Beban (Expense account)
        $akunHPP = AkunKeuangan::where('kode', '5-100')->first();
        // Kredit: Persediaan (Asset account - decrease)
        $akunPersediaan = AkunKeuangan::where('kode', '1-100')->first();

        if ($akunHPP && $akunPersediaan) {
            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnal,
                'tanggal' => $keluar->tanggal,
                'akun_id' => $akunHPP->id,
                'debit' => $total,
                'kredit' => 0,
                'keterangan' => "Pengeluaran barang: {$keluar->barang->nama}",
                'referensi_tipe' => 'barang_keluar',
                'referensi_id' => $keluar->id,
                'user_id' => auth()->id(),
            ]);

            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnal,
                'tanggal' => $keluar->tanggal,
                'akun_id' => $akunPersediaan->id,
                'debit' => 0,
                'kredit' => $total,
                'keterangan' => "Pengeluaran barang: {$keluar->barang->nama}",
                'referensi_tipe' => 'barang_keluar',
                'referensi_id' => $keluar->id,
                'user_id' => auth()->id(),
            ]);

            // Update account balances
            $akunHPP->increment('saldo', $total);
            $akunPersediaan->decrement('saldo', $total);
        }
    }

    /**
     * Record journal entry for sales revenue
     */
    private function catatJurnalPenjualan(BarangKeluar $keluar, float $totalJual): void
    {
        $kodeJurnal = JurnalEntry::generateKode();

        // Debit: Kas (Asset account - increase)
        $akunKas = AkunKeuangan::where('kode', '1-000')->first();
        // Kredit: Pendapatan Penjualan (Revenue account)
        $akunPendapatan = AkunKeuangan::where('kode', '4-000')->first();

        if ($akunKas && $akunPendapatan) {
            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnal,
                'tanggal' => $keluar->tanggal,
                'akun_id' => $akunKas->id,
                'debit' => $totalJual,
                'kredit' => 0,
                'keterangan' => "Penjualan: {$keluar->barang->nama}",
                'referensi_tipe' => 'barang_keluar',
                'referensi_id' => $keluar->id,
                'user_id' => auth()->id(),
            ]);

            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnal,
                'tanggal' => $keluar->tanggal,
                'akun_id' => $akunPendapatan->id,
                'debit' => 0,
                'kredit' => $totalJual,
                'keterangan' => "Penjualan: {$keluar->barang->nama}",
                'referensi_tipe' => 'barang_keluar',
                'referensi_id' => $keluar->id,
                'user_id' => auth()->id(),
            ]);

            // Update account balances
            $akunKas->increment('saldo', $totalJual);
            $akunPendapatan->increment('saldo', $totalJual);
        }
    }
}
