@extends('layouts.app')
@section('title', 'Laporan Stok')
@section('content')
<div class="space-y-6">
    <div class="space-y-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold" data-lang="lap_stok.heading">Laporan Persediaan Barang</h2>
            <p class="text-xs sm:text-sm" style="color:var(--text-muted)"><span data-lang="common.per_tanggal">Per tanggal</span> {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('export.stok.excel') }}" class="btn-success !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-excel"></i> Excel</a>
            <a href="{{ route('export.stok.pdf') }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-pdf"></i> PDF</a>
            <button onclick="window.print()" class="btn-outline !text-[11px] !py-1.5 !px-2.5 no-print"><i class="fas fa-print"></i> <span data-lang="common.cetak">Cetak</span></button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="glass p-4 sm:p-5 rounded-2xl stat-card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(99,102,241,0.12)"><i class="fas fa-boxes text-indigo-500"></i></div>
                <div><p class="text-xs" style="color:var(--text-muted)" data-lang="lap_stok.total_jenis">Total Jenis Barang</p><p class="text-xl sm:text-2xl font-bold">{{ number_format($totalItem) }}</p></div>
            </div>
        </div>
        <div class="glass p-4 sm:p-5 rounded-2xl stat-card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(6,182,212,0.12)"><i class="fas fa-cubes text-cyan-500"></i></div>
                <div><p class="text-xs" style="color:var(--text-muted)" data-lang="lap_stok.total_unit">Total Unit Stok</p><p class="text-xl sm:text-2xl font-bold">{{ number_format($totalStok) }}</p></div>
            </div>
        </div>
        <div class="glass p-4 sm:p-5 rounded-2xl stat-card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(16,185,129,0.12)"><i class="fas fa-coins text-emerald-500"></i></div>
                <div><p class="text-xs" style="color:var(--text-muted)" data-lang="lap_stok.total_nilai">Total Nilai Persediaan</p><p class="text-lg sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p></div>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl overflow-hidden">
        <div class="table-responsive">
            <table class="w-full text-sm text-left">
                <thead><tr style="background:var(--bg-input)">
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)" data-lang="common.no">No</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)" data-lang="brg.kode">Kode</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)" data-lang="brg.nama">Nama Barang</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider hidden md:table-cell" style="color:var(--text-muted)" data-lang="brg.kategori">Kategori</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-center hidden lg:table-cell" style="color:var(--text-muted)" data-lang="brg.metode">Metode</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)" data-lang="brg.stok">Stok</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-right hidden sm:table-cell" style="color:var(--text-muted)" data-lang="lap_stok.harga_rata">Harga Rata²</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)" data-lang="brg.total_nilai">Total Nilai</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-center" style="color:var(--text-muted)" data-lang="common.status">Status</th>
                </tr></thead>
                <tbody>
                    @foreach($barangs as $i => $b)
                    @php $status=$b->status_stok; $color=match($status){'Aman'=>'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10','Kritis'=>'text-amber-600 dark:text-amber-400 bg-amber-500/10','Reorder'=>'text-indigo-600 dark:text-indigo-400 bg-indigo-500/10','Habis'=>'text-rose-600 dark:text-rose-400 bg-rose-500/10'}; @endphp
                    <tr class="border-t transition-colors hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]" style="border-color:var(--border-color)">
                        <td class="px-4 py-3" style="color:var(--text-muted)">{{ $i+1 }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-indigo-600 dark:text-indigo-400">{{ $b->kode }}</td>
                        <td class="px-4 py-3 font-medium">{{ $b->nama }}</td>
                        <td class="px-4 py-3 hidden md:table-cell" style="color:var(--text-secondary)">{{ $b->kategori->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-center hidden lg:table-cell text-[10px] uppercase font-bold tracking-wider {{ $b->metode_stok=='fifo'?'text-cyan-600 dark:text-cyan-400':'text-fuchsia-600 dark:text-fuchsia-400' }}">{{ $b->metode_stok }}</td>
                        <td class="px-4 py-3 text-right font-bold">{{ number_format($b->stok) }} <span class="text-xs font-normal" style="color:var(--text-muted)">{{ $b->satuan }}</span></td>
                        <td class="px-4 py-3 text-right hidden sm:table-cell" style="color:var(--text-secondary)">Rp {{ number_format($b->harga_rata_rata,0,',','.') }}</td>
                        <td class="px-4 py-3 text-right text-cyan-600 dark:text-cyan-400 font-medium">Rp {{ number_format($b->total_nilai,0,',','.') }}</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] {{ $color }}">{{ $status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot><tr style="background:var(--bg-input)">
                    <td colspan="7" class="px-4 py-3 text-right font-semibold" style="color:var(--text-secondary)" data-lang="lap_stok.grand_total">Grand Total</td>
                    <td class="px-4 py-3 text-right font-bold text-lg text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalNilai,0,',','.') }}</td>
                    <td></td>
                </tr></tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
