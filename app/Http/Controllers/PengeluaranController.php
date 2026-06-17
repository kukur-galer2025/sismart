<?php

namespace App\Http\Controllers;

use App\Models\AkunKeuangan;
use App\Models\JurnalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function create()
    {
        // Get expense and prive accounts
        $akuns = AkunKeuangan::whereIn('tipe', ['beban', 'ekuitas'])
            ->where('kode', '!=', '3-000') // Exclude Modal
            ->orderBy('kode')
            ->get();
            
        // Get latest expenses for history
        $history = JurnalEntry::with('akun', 'user')
            ->whereHas('akun', function($q) {
                $q->whereIn('tipe', ['beban', 'ekuitas'])->where('kode', '!=', '3-000');
            })
            ->where('debit', '>', 0)
            ->latest('tanggal')
            ->latest('id')
            ->take(10)
            ->get();

        return view('keuangan.pengeluaran', compact('akuns', 'history'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'akun_id' => 'required|exists:akun_keuangans,id',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $akunBeban = AkunKeuangan::findOrFail($request->akun_id);
            $akunKas = AkunKeuangan::where('kode', '1-000')->firstOrFail();

            $kodeJurnal = JurnalEntry::generateKode();
            
            // Create journal for Expense/Prive (Debit)
            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnal,
                'akun_id' => $akunBeban->id,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'debit' => $request->nominal,
                'kredit' => 0,
                'user_id' => auth()->id(),
            ]);

            // Create journal for Kas (Credit)
            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnal,
                'akun_id' => $akunKas->id,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'debit' => 0,
                'kredit' => $request->nominal,
                'user_id' => auth()->id(),
            ]);

            // Update Account Balances
            $akunBeban->increment('saldo', $request->nominal);
            $akunKas->decrement('saldo', $request->nominal);
        });

        return redirect()->route('keuangan.pengeluaran.create')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy($kode_jurnal)
    {
        DB::transaction(function () use ($kode_jurnal) {
            $jurnals = JurnalEntry::where('kode_jurnal', $kode_jurnal)->get();
            
            if ($jurnals->isEmpty()) {
                throw new \Exception('Data jurnal tidak ditemukan.');
            }

            foreach ($jurnals as $jurnal) {
                $akun = AkunKeuangan::find($jurnal->akun_id);
                if ($akun) {
                    // Reverse the balances
                    if ($jurnal->debit > 0) {
                        // If it was debited (e.g. Expense increased), we decrease it
                        $akun->decrement('saldo', $jurnal->debit);
                    }
                    if ($jurnal->kredit > 0) {
                        // If it was credited (e.g. Kas decreased), we increase it
                        $akun->increment('saldo', $jurnal->kredit);
                    }
                }
                $jurnal->delete();
            }
        });

        return redirect()->route('keuangan.pengeluaran.create')->with('success', 'Data pengeluaran berhasil dihapus dan saldo telah dikembalikan!');
    }
}
