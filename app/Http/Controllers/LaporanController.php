<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function stok(Request $request)
    {
        $query = Barang::with('kategori')->where('is_active', true);

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $barangs = $query->orderBy('nama')->get();

        $totalNilai = $barangs->sum('total_nilai');
        $totalItem = $barangs->count();
        $totalStok = $barangs->sum('stok');

        return view('laporan.stok', compact('barangs', 'totalNilai', 'totalItem', 'totalStok'));
    }

    public function transaksi(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $masuk = BarangMasuk::with('barang', 'user')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get();

        $keluar = BarangKeluar::with('barang', 'user')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalMasuk = $masuk->sum('total_harga');
        $totalKeluar = $keluar->sum('total_harga');

        return view('laporan.transaksi', compact('masuk', 'keluar', 'totalMasuk', 'totalKeluar', 'dari', 'sampai'));
    }

    public function perputaran(Request $request)
    {
        $barangs = Barang::with('kategori')->where('is_active', true)->get();

        $data = $barangs->map(function ($barang) {
            $totalKeluar = $barang->barangKeluars()
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('total_harga');

            $rataStok = $barang->total_nilai > 0 ? $barang->total_nilai : 1;
            $turnover = round($totalKeluar / $rataStok, 2);

            return [
                'barang' => $barang,
                'total_keluar' => $totalKeluar,
                'rata_stok' => $barang->total_nilai,
                'turnover' => $turnover,
            ];
        });

        return view('laporan.perputaran', compact('data'));
    }
}
