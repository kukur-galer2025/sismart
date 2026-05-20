@extends('layouts.app')
@section('title', 'Neraca')
@section('content')
<div class="space-y-6">
    <div class="space-y-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold">Neraca (Balance Sheet)</h2>
            <p class="text-xs sm:text-sm" style="color:var(--text-muted)">Per {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
            <form method="GET" action="{{ route('keuangan.neraca') }}" class="flex items-center gap-1.5 flex-wrap">
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <button type="submit" class="btn-primary !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-eye mr-1"></i>Lihat</button>
            </form>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('export.neraca.pdf', ['tanggal'=>$tanggal]) }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-pdf"></i> PDF</a>
                <a href="{{ route('export.neraca.excel', ['tanggal'=>$tanggal]) }}" class="btn-success !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-excel"></i> Excel</a>
            </div>
        </div>
    </div>
    <div class="max-w-5xl mx-auto">
        <div class="glass rounded-2xl overflow-hidden">
            <div class="p-5 text-center border-b" style="border-color:var(--border-color); background:var(--bg-input)">
                <h3 class="text-lg font-bold">SISmart — Neraca</h3>
                <p class="text-xs" style="color:var(--text-muted)">Per {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-4 sm:p-6 border-b md:border-b-0 md:border-r" style="border-color:var(--border-color)">
                    <h4 class="font-semibold text-cyan-700 dark:text-cyan-400 mb-4 text-xs uppercase tracking-wider pb-3 border-b" style="border-color:var(--border-color)"><i class="fas fa-building-columns mr-2"></i>ASET (AKTIVA)</h4>
                    @foreach($aset as $a)
                    <div class="flex justify-between py-2 px-3 rounded-lg hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]">
                        <span class="text-sm" style="color:var(--text-secondary)">{{ $a->nama }}</span>
                        <span class="text-sm font-medium font-mono">Rp {{ number_format($a->saldo_periode,0,',','.') }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between py-3 px-3 mt-4 rounded-xl" style="background:rgba(6,182,212,0.1)">
                        <span class="text-cyan-700 dark:text-cyan-400 font-semibold text-sm">Total Aset</span>
                        <span class="text-cyan-700 dark:text-cyan-400 font-bold text-lg font-mono">Rp {{ number_format($totalAset,0,',','.') }}</span>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <h4 class="font-semibold text-amber-700 dark:text-amber-400 mb-4 text-xs uppercase tracking-wider pb-3 border-b" style="border-color:var(--border-color)"><i class="fas fa-hand-holding-dollar mr-2"></i>KEWAJIBAN</h4>
                    @forelse($kewajiban as $k)
                    <div class="flex justify-between py-2 px-3 rounded-lg hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]">
                        <span class="text-sm" style="color:var(--text-secondary)">{{ $k->nama }}</span>
                        <span class="text-sm font-medium font-mono">Rp {{ number_format($k->saldo_periode,0,',','.') }}</span>
                    </div>
                    @empty
                    <p class="py-2 px-3 text-sm" style="color:var(--text-muted)">Tidak ada kewajiban</p>
                    @endforelse
                    <div class="flex justify-between py-2.5 px-3 rounded-lg mt-3 mb-6" style="background:rgba(245,158,11,0.1)">
                        <span class="text-amber-700 dark:text-amber-400 font-semibold text-sm">Total Kewajiban</span>
                        <span class="text-amber-700 dark:text-amber-400 font-bold font-mono">Rp {{ number_format($totalKewajiban,0,',','.') }}</span>
                    </div>
                    <h4 class="font-semibold text-purple-700 dark:text-purple-400 mb-4 text-xs uppercase tracking-wider pb-3 border-b" style="border-color:var(--border-color)"><i class="fas fa-landmark mr-2"></i>EKUITAS (MODAL)</h4>
                    @foreach($ekuitas as $e)
                    <div class="flex justify-between py-2 px-3 rounded-lg hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]">
                        <span class="text-sm" style="color:var(--text-secondary)">{{ $e->nama }}</span>
                        <span class="text-sm font-medium font-mono">Rp {{ number_format($e->saldo_periode,0,',','.') }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between py-2.5 px-3 rounded-lg mt-3 mb-4" style="background:rgba(139,92,246,0.1)">
                        <span class="text-purple-700 dark:text-purple-400 font-semibold text-sm">Total Ekuitas</span>
                        <span class="text-purple-700 dark:text-purple-400 font-bold font-mono">Rp {{ number_format($totalEkuitas,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between py-3 px-3 rounded-xl" style="background:rgba(6,182,212,0.1)">
                        <span class="text-cyan-700 dark:text-cyan-400 font-semibold text-sm">Kewajiban + Ekuitas</span>
                        <span class="text-cyan-700 dark:text-cyan-400 font-bold text-lg font-mono">Rp {{ number_format($totalKewajiban+$totalEkuitas,0,',','.') }}</span>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t text-center" style="border-color:var(--border-color)">
                @if(abs($totalAset-($totalKewajiban+$totalEkuitas))<1)
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-emerald-700 dark:text-emerald-400 text-sm" style="background:rgba(16,185,129,0.1)"><i class="fas fa-check-circle"></i> Neraca SEIMBANG</span>
                @else
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-rose-700 dark:text-rose-400 text-sm" style="background:rgba(239,68,68,0.1)"><i class="fas fa-exclamation-triangle"></i> TIDAK SEIMBANG — Selisih: Rp {{ number_format(abs($totalAset-($totalKewajiban+$totalEkuitas)),0,',','.') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
