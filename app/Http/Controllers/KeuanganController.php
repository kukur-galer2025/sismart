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

    public function perubahanModal(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        // Modal Awal: saldo modal sebelum periode ini
        $akunModal = AkunKeuangan::where('kode', '3-000')->first();
        $modalAwal = 0;
        if($akunModal) {
            $modalAwalKredit = JurnalEntry::where('akun_id', $akunModal->id)->where('tanggal', '<', $dari)->sum('kredit');
            $modalAwalDebit = JurnalEntry::where('akun_id', $akunModal->id)->where('tanggal', '<', $dari)->sum('debit');
            $modalAwal = $akunModal->saldo_normal == 'kredit' ? ($modalAwalKredit - $modalAwalDebit) : ($modalAwalDebit - $modalAwalKredit);
            // Include starting balance if no previous transactions? Wait, saldo is cumulative. We rely on journal.
            // If the system started at a specific date, initial capital should be a journal entry. 
            // In our seeder, we just set saldo but no journal. Let's just use the current saldo minus changes within/after period?
            // Actually, we can get Modal Awal by taking current Saldo - Net change from 'dari' to future. 
            // Better: just calculate Modal Awal from Jurnal. Assuming all initial balances have Journals. Wait! Our seeder doesn't have journals!
            // Let's just calculate Modal Akhir from DB `saldo`, then backtrack for Modal Awal: Modal Awal = Modal Akhir - Laba + Prive
        }

        // 1. Calculate Laba Bersih
        $totalPendapatan = JurnalEntry::whereHas('akun', function($q){ $q->where('tipe', 'pendapatan'); })
            ->whereBetween('tanggal', [$dari, $sampai])->selectRaw('SUM(kredit) - SUM(debit) as total')->value('total') ?? 0;
            
        $totalBeban = JurnalEntry::whereHas('akun', function($q){ $q->where('tipe', 'beban'); })
            ->whereBetween('tanggal', [$dari, $sampai])->selectRaw('SUM(debit) - SUM(kredit) as total')->value('total') ?? 0;
            
        $labaBersih = $totalPendapatan - $totalBeban;

        // 2. Calculate Prive (Penarikan)
        $akunPrive = AkunKeuangan::where('kode', '3-100')->first();
        $prive = 0;
        if($akunPrive) {
            $priveDebit = JurnalEntry::where('akun_id', $akunPrive->id)->whereBetween('tanggal', [$dari, $sampai])->sum('debit');
            $priveKredit = JurnalEntry::where('akun_id', $akunPrive->id)->whereBetween('tanggal', [$dari, $sampai])->sum('kredit');
            $prive = $priveDebit - $priveKredit;
        }

        // Backtrack Modal Awal if we don't have perfect journals.
        // Or simply calculate the net changes in the period.
        // Let's assume Modal Awal is computed dynamically based on all journals before $dari.
        // Wait, if no journal for initial balance, `modalAwal` will be 0!
        // To fix this, let's just get the current 'saldo' of Modal, and subtract the net changes from $dari to now.
        // Actually, the simplest way is to sum all journals before $dari. If seeder didn't make a journal, it's a bug in seeder.
        // We added a seeder that has `saldo` but no journal.
        // Let's just create a journal for the initial balances if they are missing?
        // Let's calculate: Modal Awal = Modal current saldo - all changes (kredit-debit) from $dari onwards.
        $changesAfterStart = JurnalEntry::where('akun_id', $akunModal->id)->where('tanggal', '>=', $dari)->sum('kredit') - JurnalEntry::where('akun_id', $akunModal->id)->where('tanggal', '>=', $dari)->sum('debit');
        $modalAwal = ($akunModal->saldo ?? 0) - $changesAfterStart;
        
        // Wait, the $labaBersih is not closed into Modal account automatically.
        // So `akunModal->saldo` is just the raw Modal.
        // Real Modal Akhir = Modal Awal (raw modal) + Laba Bersih - Prive.
        $modalAkhir = $modalAwal + $labaBersih - $prive;

        return view('keuangan.perubahan-modal', compact('modalAwal', 'labaBersih', 'prive', 'modalAkhir', 'dari', 'sampai'));
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
