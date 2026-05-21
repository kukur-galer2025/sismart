<?php

namespace App\Http\Controllers;

use App\Exports\LaporanStokExport;
use App\Exports\LaporanTransaksiExport;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\AkunKeuangan;
use App\Models\JurnalEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    // ============ EXCEL EXPORTS ============

    public function stokExcel()
    {
        return Excel::download(new LaporanStokExport, 'Laporan_Stok_' . now()->format('Ymd') . '.xlsx');
    }

    public function transaksiExcel(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');
        return Excel::download(
            new LaporanTransaksiExport($dari, $sampai),
            'Laporan_Transaksi_' . $dari . '_' . $sampai . '.xlsx'
        );
    }

    public function jurnalExcel(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $jurnals = JurnalEntry::with('akun')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Simple CSV-style Excel via collection export
        $data = $jurnals->map(function ($j) {
            return [
                __('export.tanggal') => $j->tanggal->format('d/m/Y'),
                __('export.akun') => $j->akun->nama ?? '-',
                __('export.keterangan') => $j->keterangan ?? '-',
                __('export.debit') => $j->debit,
                __('export.kredit') => $j->kredit,
            ];
        });

        return Excel::download(new \App\Exports\CollectionExport($data, __('export.excel.jurnal_sheet')), 'Jurnal_' . $dari . '_' . $sampai . '.xlsx');
    }

    public function barangMasukExcel(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $data = BarangMasuk::with('barang', 'user')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($m) {
                return [
                    __('export.tanggal') => $m->tanggal->format('d/m/Y'),
                    __('export.kode') => $m->barang->kode ?? '-',
                    __('export.nama_barang') => $m->barang->nama ?? '-',
                    __('export.jumlah') => $m->jumlah,
                    __('export.harga_satuan') => $m->harga_satuan,
                    __('export.total') => $m->total_harga,
                    __('export.supplier') => $m->supplier ?? '-',
                    __('export.excel.petugas') => $m->user->name ?? '-',
                ];
            });

        return Excel::download(new \App\Exports\CollectionExport($data, __('export.excel.masuk_sheet')), 'Barang_Masuk_' . $dari . '_' . $sampai . '.xlsx');
    }

    public function barangKeluarExcel(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $data = BarangKeluar::with('barang', 'user')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($k) {
                return [
                    __('export.tanggal') => $k->tanggal->format('d/m/Y'),
                    __('export.kode') => $k->barang->kode ?? '-',
                    __('export.nama_barang') => $k->barang->nama ?? '-',
                    __('export.jumlah') => $k->jumlah,
                    __('export.harga_satuan') => $k->harga_satuan,
                    __('export.total') => $k->total_harga,
                    __('export.tujuan') => $k->tujuan ?? '-',
                    __('export.excel.petugas') => $k->user->name ?? '-',
                ];
            });

        return Excel::download(new \App\Exports\CollectionExport($data, __('export.excel.keluar_sheet')), 'Barang_Keluar_' . $dari . '_' . $sampai . '.xlsx');
    }

    public function perputaranExcel()
    {
        $data = Barang::with('kategori')
            ->where('is_active', true)
            ->get()
            ->map(function ($b) {
                $masuk = $b->barangMasuks()->sum('jumlah');
                $keluar = $b->barangKeluars()->sum('jumlah');
                $avg = ($masuk + $b->stok) > 0 ? round($keluar / (($masuk + $b->stok) / 2), 2) : 0;
                return [
                    __('export.kode') => $b->kode,
                    __('export.nama_barang') => $b->nama,
                    __('export.kategori') => $b->kategori->nama ?? '-',
                    __('export.stok') => $b->stok,
                    __('export.total_masuk_label') => $masuk,
                    __('export.total_keluar_label') => $keluar,
                    __('export.excel.rasio_perputaran') => $avg,
                ];
            });

        return Excel::download(new \App\Exports\CollectionExport($data, __('export.excel.perputaran_sheet')), 'Perputaran_Stok_' . now()->format('Ymd') . '.xlsx');
    }

    // ============ PDF EXPORTS ============

    public function stokPdf()
    {
        $barangs = Barang::with('kategori')->where('is_active', true)->orderBy('nama')->get();
        $totalNilai = $barangs->sum('total_nilai');
        $totalStok = $barangs->sum('stok');
        $totalItem = $barangs->count();

        $pdf = Pdf::loadView('exports.stok-pdf', compact('barangs', 'totalNilai', 'totalStok', 'totalItem'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_Stok_' . now()->format('Ymd') . '.pdf');
    }

    public function transaksiPdf(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $masuk = BarangMasuk::with('barang', 'user')->whereBetween('tanggal', [$dari, $sampai])->orderBy('tanggal', 'desc')->get();
        $keluar = BarangKeluar::with('barang', 'user')->whereBetween('tanggal', [$dari, $sampai])->orderBy('tanggal', 'desc')->get();
        $totalMasuk = $masuk->sum('total_harga');
        $totalKeluar = $keluar->sum('total_harga');

        $pdf = Pdf::loadView('exports.transaksi-pdf', compact('masuk', 'keluar', 'totalMasuk', 'totalKeluar', 'dari', 'sampai'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_Transaksi_' . $dari . '_' . $sampai . '.pdf');
    }

    public function labaRugiPdf(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $pendapatan = AkunKeuangan::where('tipe', 'pendapatan')->get()->map(function ($akun) use ($dari, $sampai) {
            $akun->saldo_periode = JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('kredit')
                                 - JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('debit');
            return $akun;
        });
        $beban = AkunKeuangan::where('tipe', 'beban')->get()->map(function ($akun) use ($dari, $sampai) {
            $akun->saldo_periode = JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('debit')
                                 - JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('kredit');
            return $akun;
        });
        $totalPendapatan = $pendapatan->sum('saldo_periode');
        $totalBeban = $beban->sum('saldo_periode');
        $labaRugi = $totalPendapatan - $totalBeban;

        $pdf = Pdf::loadView('exports.laba-rugi-pdf', compact('pendapatan', 'beban', 'totalPendapatan', 'totalBeban', 'labaRugi', 'dari', 'sampai'));
        return $pdf->download('Laba_Rugi_' . $dari . '_' . $sampai . '.pdf');
    }

    public function neracaPdf(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');

        $aset = AkunKeuangan::where('tipe', 'aset')->get()->map(function ($akun) use ($tanggal) {
            $akun->saldo_periode = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('debit')
                                 - JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('kredit');
            return $akun;
        });
        $kewajiban = AkunKeuangan::where('tipe', 'kewajiban')->get()->map(function ($akun) use ($tanggal) {
            $akun->saldo_periode = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('kredit')
                                 - JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('debit');
            return $akun;
        });
        $ekuitas = AkunKeuangan::where('tipe', 'ekuitas')->get()->map(function ($akun) use ($tanggal) {
            $akun->saldo_periode = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('kredit')
                                 - JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('debit');
            return $akun;
        });
        $totalAset = $aset->sum('saldo_periode');
        $totalKewajiban = $kewajiban->sum('saldo_periode');
        $totalEkuitas = $ekuitas->sum('saldo_periode');

        $pdf = Pdf::loadView('exports.neraca-pdf', compact('aset', 'kewajiban', 'ekuitas', 'totalAset', 'totalKewajiban', 'totalEkuitas', 'tanggal'));
        return $pdf->download('Neraca_' . $tanggal . '.pdf');
    }

    public function jurnalPdf(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $jurnals = JurnalEntry::with('akun')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');

        $pdf = Pdf::loadView('exports.jurnal-pdf', compact('jurnals', 'totalDebit', 'totalKredit', 'dari', 'sampai'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Jurnal_' . $dari . '_' . $sampai . '.pdf');
    }

    public function barangMasukPdf(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $data = BarangMasuk::with('barang', 'user')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get();
        $total = $data->sum('total_harga');

        $pdf = Pdf::loadView('exports.barang-masuk-pdf', compact('data', 'total', 'dari', 'sampai'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Barang_Masuk_' . $dari . '_' . $sampai . '.pdf');
    }

    public function barangKeluarPdf(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $data = BarangKeluar::with('barang', 'user')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'desc')
            ->get();
        $total = $data->sum('total_harga');

        $pdf = Pdf::loadView('exports.barang-keluar-pdf', compact('data', 'total', 'dari', 'sampai'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Barang_Keluar_' . $dari . '_' . $sampai . '.pdf');
    }

    public function perputaranPdf()
    {
        $barangs = Barang::with('kategori')->where('is_active', true)->get()->map(function ($b) {
            $b->total_masuk = $b->barangMasuks()->sum('jumlah');
            $b->total_keluar = $b->barangKeluars()->sum('jumlah');
            $b->rasio = ($b->total_masuk + $b->stok) > 0 ? round($b->total_keluar / (($b->total_masuk + $b->stok) / 2), 2) : 0;
            return $b;
        });

        $pdf = Pdf::loadView('exports.perputaran-pdf', compact('barangs'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Perputaran_Stok_' . now()->format('Ymd') . '.pdf');
    }

    public function labaRugiExcel(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $pendapatan = AkunKeuangan::where('tipe', 'pendapatan')->get()->map(function ($akun) use ($dari, $sampai) {
            $akun->saldo_periode = JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('kredit')
                                 - JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('debit');
            return $akun;
        });
        $beban = AkunKeuangan::where('tipe', 'beban')->get()->map(function ($akun) use ($dari, $sampai) {
            $akun->saldo_periode = JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('debit')
                                 - JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('kredit');
            return $akun;
        });

        $data = collect();
        foreach ($pendapatan as $p) {
            $data->push([__('export.excel.tipe') => __('export.pendapatan'), __('export.kode') => $p->kode, __('export.excel.nama_akun') => $p->nama, __('export.excel.saldo') => $p->saldo_periode]);
        }
        foreach ($beban as $b) {
            $data->push([__('export.excel.tipe') => __('export.beban'), __('export.kode') => $b->kode, __('export.excel.nama_akun') => $b->nama, __('export.excel.saldo') => $b->saldo_periode]);
        }

        return Excel::download(new \App\Exports\CollectionExport($data, __('export.excel.laba_rugi_sheet')), 'Laba_Rugi_' . $dari . '_' . $sampai . '.xlsx');
    }

    public function neracaExcel(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');

        $types = ['aset', 'kewajiban', 'ekuitas'];
        $data = collect();

        foreach ($types as $tipe) {
            $akuns = AkunKeuangan::where('tipe', $tipe)->get();
            foreach ($akuns as $akun) {
                if (in_array($tipe, ['kewajiban', 'ekuitas'])) {
                    $saldo = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('kredit')
                           - JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('debit');
                } else {
                    $saldo = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('debit')
                           - JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('kredit');
                }
                $data->push([__('export.excel.tipe') => ucfirst($tipe), __('export.kode') => $akun->kode, __('export.excel.nama_akun') => $akun->nama, __('export.excel.saldo') => $saldo]);
            }
        }

        return Excel::download(new \App\Exports\CollectionExport($data, __('export.excel.neraca_sheet')), 'Neraca_' . $tanggal . '.xlsx');
    }
}
