@extends('layouts.app')
@section('title', 'Perputaran Stok')
@section('content')
<div class="space-y-6">
    <div class="space-y-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold" data-lang="perputaran.heading">Inventory Turnover</h2>
            <p class="text-xs sm:text-sm" style="color:var(--text-muted)"><span data-lang="perputaran.subtitle">Perputaran stok bulan</span> {{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('export.perputaran.pdf') }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-pdf"></i> PDF</a>
            <a href="{{ route('export.perputaran.excel') }}" class="btn-success !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-excel"></i> Excel</a>
            <button onclick="window.print()" class="btn-outline !text-[11px] !py-1.5 !px-2.5 no-print"><i class="fas fa-print"></i> Cetak</button>
        </div>
    </div>

    <div class="p-4 rounded-xl text-sm text-indigo-700 dark:text-indigo-300" style="background:rgba(99,102,241,0.08)">
        <i class="fas fa-info-circle mr-2"></i><strong>Inventory Turnover</strong> = Total Nilai Keluar / Nilai Persediaan. Semakin tinggi → semakin efisien.
    </div>

    <div class="sm:hidden space-y-3">
        @foreach($data as $d)
        @php $ef=$d['turnover']>=2?['Tinggi','text-emerald-600 dark:text-emerald-400','rgba(16,185,129,0.1)']:($d['turnover']>=1?['Sedang','text-amber-600 dark:text-amber-400','rgba(245,158,11,0.1)']:['Rendah','text-rose-600 dark:text-rose-400','rgba(239,68,68,0.1)']); @endphp
        <div class="glass rounded-xl p-4">
            <div class="flex items-start justify-between mb-2">
                <div><p class="font-medium text-sm">{{ $d['barang']->nama }}</p><p class="text-[10px] font-mono" style="color:var(--text-muted)">{{ $d['barang']->kode }}</p></div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium {{ $ef[1] }}" style="background:{{ $ef[2] }}">{{ $ef[0] }}</span>
            </div>
            <div class="flex items-center justify-between text-xs mt-2">
                <span style="color:var(--text-muted)">Turnover</span>
                <span class="text-lg font-bold {{ $d['turnover']>=1?'text-emerald-600 dark:text-emerald-400':'' }}" style="{{ $d['turnover']<1?'color:var(--text-muted)':'' }}">{{ $d['turnover'] }}x</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="glass rounded-2xl overflow-hidden hidden sm:block">
        <div class="table-responsive">
            <table class="w-full text-sm text-left">
                <thead><tr style="background:var(--bg-input)">
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">No</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Barang</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider hidden md:table-cell" style="color:var(--text-muted)">Kategori</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-right hidden lg:table-cell" style="color:var(--text-muted)">Nilai Keluar</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-right hidden lg:table-cell" style="color:var(--text-muted)">Rata² Persediaan</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-center" style="color:var(--text-muted)">Turnover</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-center" style="color:var(--text-muted)">Efisiensi</th>
                </tr></thead>
                <tbody>
                    @foreach($data as $i => $d)
                    @php $ef=$d['turnover']>=2?['Tinggi','text-emerald-600 dark:text-emerald-400','rgba(16,185,129,0.1)']:($d['turnover']>=1?['Sedang','text-amber-600 dark:text-amber-400','rgba(245,158,11,0.1)']:['Rendah','text-rose-600 dark:text-rose-400','rgba(239,68,68,0.1)']); @endphp
                    <tr class="border-t transition-colors hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]" style="border-color:var(--border-color)">
                        <td class="px-5 py-3" style="color:var(--text-muted)">{{ $i+1 }}</td>
                        <td class="px-5 py-3"><p class="font-medium">{{ $d['barang']->nama }}</p><p class="text-[10px] font-mono" style="color:var(--text-muted)">{{ $d['barang']->kode }}</p></td>
                        <td class="px-5 py-3 hidden md:table-cell" style="color:var(--text-secondary)">{{ $d['barang']->kategori->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-right hidden lg:table-cell" style="color:var(--text-secondary)">Rp {{ number_format($d['total_keluar'],0,',','.') }}</td>
                        <td class="px-5 py-3 text-right hidden lg:table-cell" style="color:var(--text-secondary)">Rp {{ number_format($d['rata_stok'],0,',','.') }}</td>
                        <td class="px-5 py-3 text-center"><span class="text-lg font-bold {{ $d['turnover']>=1?'text-emerald-600 dark:text-emerald-400':'' }}" style="{{ $d['turnover']<1?'color:var(--text-muted)':'' }}">{{ $d['turnover'] }}x</span></td>
                        <td class="px-5 py-3 text-center"><span class="px-3 py-1 rounded-full text-[10px] font-medium {{ $ef[1] }}" style="background:{{ $ef[2] }}">{{ $ef[0] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
