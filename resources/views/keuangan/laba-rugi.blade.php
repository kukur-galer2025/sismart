@extends('layouts.app')
@section('title', 'Laporan Laba Rugi')
@section('content')
<div class="space-y-6">
    <div class="space-y-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold">Laporan Laba Rugi</h2>
            <p class="text-xs sm:text-sm" style="color:var(--text-muted)">{{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
            <form method="GET" action="{{ route('keuangan.laba-rugi') }}" class="flex items-center gap-1.5 flex-wrap">
                <input type="date" name="dari" value="{{ $dari }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <span class="text-[10px]" style="color:var(--text-muted)">s/d</span>
                <input type="date" name="sampai" value="{{ $sampai }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <button type="submit" class="btn-primary !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-filter"></i> Filter</button>
            </form>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('export.laba-rugi.pdf', ['dari'=>$dari,'sampai'=>$sampai]) }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-pdf"></i> PDF</a>
                <a href="{{ route('export.laba-rugi.excel', ['dari'=>$dari,'sampai'=>$sampai]) }}" class="btn-success !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-excel"></i> Excel</a>
            </div>
        </div>
    </div>
    <div class="max-w-3xl mx-auto">
        <div class="glass rounded-2xl overflow-hidden">
            <div class="p-5 text-center border-b" style="border-color:var(--border-color); background:var(--bg-input)">
                <h3 class="text-lg font-bold">SISmart — Laporan Laba Rugi</h3>
                <p class="text-xs mt-1" style="color:var(--text-muted)">{{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="p-4 sm:p-6 space-y-6">
                <div>
                    <h4 class="font-semibold text-emerald-700 dark:text-emerald-400 mb-3 text-sm uppercase tracking-wider"><i class="fas fa-arrow-trend-up mr-2"></i>Pendapatan</h4>
                    @forelse($pendapatan as $p)
                    <div class="flex justify-between items-center py-2.5 px-4 rounded-lg hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]">
                        <span class="text-sm" style="color:var(--text-secondary)">{{ $p->nama }} <span style="color:var(--text-muted)">({{ $p->kode }})</span></span>
                        <span class="text-sm font-medium font-mono">Rp {{ number_format($p->saldo_periode,0,',','.') }}</span>
                    </div>
                    @empty
                    <p class="px-4 py-2 text-sm" style="color:var(--text-muted)">Belum ada pendapatan</p>
                    @endforelse
                    <div class="flex justify-between items-center py-3 px-4 mt-2 rounded-xl" style="background:rgba(16,185,129,0.1)">
                        <span class="text-emerald-700 dark:text-emerald-400 font-semibold text-sm">Total Pendapatan</span>
                        <span class="text-emerald-700 dark:text-emerald-400 font-bold text-lg font-mono">Rp {{ number_format($totalPendapatan,0,',','.') }}</span>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-rose-700 dark:text-rose-400 mb-3 text-sm uppercase tracking-wider"><i class="fas fa-arrow-trend-down mr-2"></i>Beban</h4>
                    @forelse($beban as $b)
                    <div class="flex justify-between items-center py-2.5 px-4 rounded-lg hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]">
                        <span class="text-sm" style="color:var(--text-secondary)">{{ $b->nama }} <span style="color:var(--text-muted)">({{ $b->kode }})</span></span>
                        <span class="text-sm font-medium font-mono">Rp {{ number_format($b->saldo_periode,0,',','.') }}</span>
                    </div>
                    @empty
                    <p class="px-4 py-2 text-sm" style="color:var(--text-muted)">Belum ada beban</p>
                    @endforelse
                    <div class="flex justify-between items-center py-3 px-4 mt-2 rounded-xl" style="background:rgba(239,68,68,0.1)">
                        <span class="text-rose-700 dark:text-rose-400 font-semibold text-sm">Total Beban</span>
                        <span class="text-rose-700 dark:text-rose-400 font-bold text-lg font-mono">Rp {{ number_format($totalBeban,0,',','.') }}</span>
                    </div>
                </div>
                <div class="border-t pt-6" style="border-color:var(--border-color)">
                    <div class="flex flex-col sm:flex-row justify-between items-center p-5 rounded-2xl gap-4" style="background:{{ $labaRugi >= 0 ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }}">
                        <div class="text-center sm:text-left">
                            <p class="text-sm {{ $labaRugi >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }} font-semibold uppercase tracking-wider"><i class="fas {{ $labaRugi >= 0 ? 'fa-chart-line' : 'fa-chart-line-down' }} mr-1"></i>{{ $labaRugi >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</p>
                            <p class="text-xs mt-1" style="color:var(--text-muted)">Pendapatan - Beban</p>
                        </div>
                        <span class="text-3xl font-bold font-mono {{ $labaRugi >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">Rp {{ number_format(abs($labaRugi),0,',','.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
