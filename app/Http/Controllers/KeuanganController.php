<?php

namespace App\Http\Controllers;

use App\Models\AkunKeuangan;
use App\Models\JurnalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function labaRugi(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $pendapatan = AkunKeuangan::where('tipe', 'pendapatan')->get()->map(function ($akun) use ($dari, $sampai) {
            $kredit = JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('kredit');
            $debit = JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('debit');
            $akun->saldo_periode = $kredit - $debit;
            return $akun;
        });

        $beban = AkunKeuangan::where('tipe', 'beban')->get()->map(function ($akun) use ($dari, $sampai) {
            $debit = JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('debit');
            $kredit = JurnalEntry::where('akun_id', $akun->id)->whereBetween('tanggal', [$dari, $sampai])->sum('kredit');
            $akun->saldo_periode = $debit - $kredit;
            return $akun;
        });

        $totalPendapatan = $pendapatan->sum('saldo_periode');
        $totalBeban = $beban->sum('saldo_periode');
        $labaRugi = $totalPendapatan - $totalBeban;

        return view('keuangan.laba-rugi', compact('pendapatan', 'beban', 'totalPendapatan', 'totalBeban', 'labaRugi', 'dari', 'sampai'));
    }

    public function neraca(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');

        $aset = AkunKeuangan::where('tipe', 'aset')->get()->map(function ($akun) use ($tanggal) {
            $debit = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('debit');
            $kredit = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('kredit');
            $akun->saldo_periode = $debit - $kredit;
            return $akun;
        });

        $kewajiban = AkunKeuangan::where('tipe', 'kewajiban')->get()->map(function ($akun) use ($tanggal) {
            $kredit = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('kredit');
            $debit = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('debit');
            $akun->saldo_periode = $kredit - $debit;
            return $akun;
        });

        $ekuitas = AkunKeuangan::where('tipe', 'ekuitas')->get()->map(function ($akun) use ($tanggal) {
            $kredit = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('kredit');
            $debit = JurnalEntry::where('akun_id', $akun->id)->where('tanggal', '<=', $tanggal)->sum('debit');
            $akun->saldo_periode = $kredit - $debit;
            return $akun;
        });

        $totalAset = $aset->sum('saldo_periode');
        $totalKewajiban = $kewajiban->sum('saldo_periode');
        $totalEkuitas = $ekuitas->sum('saldo_periode');

        return view('keuangan.neraca', compact('aset', 'kewajiban', 'ekuitas', 'totalAset', 'totalKewajiban', 'totalEkuitas', 'tanggal'));
    }

    public function jurnal(Request $request)
    {
        $query = JurnalEntry::with('akun', 'user');
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }
        $jurnals = $query->latest('tanggal')->latest('id')->paginate(20)->withQueryString();
        return view('keuangan.jurnal', compact('jurnals'));
    }
}
