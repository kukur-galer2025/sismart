@extends('layouts.app')
@section('title', 'Transaksi Barang Keluar')
@section('content')
<div class="space-y-6">
    <div class="space-y-3">
        <form method="GET" action="{{ route('barang-keluar.index') }}" class="flex items-center gap-1.5 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="form-input !w-full sm:!w-32 !py-1.5 !text-[11px]">
            <input type="date" name="dari" value="{{ request('dari') }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
            <span class="text-[10px] hidden sm:inline" style="color:var(--text-muted)" data-lang="common.sd">s/d</span>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
            <select onchange="if(this.value){ let d=new Date(), s=new Date(); if(this.value==='7d')s.setDate(d.getDate()-7); if(this.value==='1m')s.setMonth(d.getMonth()-1); if(this.value==='1y')s.setFullYear(d.getFullYear()-1); let f=this.closest('form'); f.dari.value=s.toISOString().split('T')[0]; f.sampai.value=d.toISOString().split('T')[0]; f.submit(); }" class="form-input !w-auto !py-1.5 !text-[11px] font-medium cursor-pointer" style="color:var(--text-secondary)">
                <option value="" data-lang="filter.preset">Pilih Periode...</option>
                <option value="7d" data-lang="filter.7d">7 Hari Terakhir</option>
                <option value="1m" data-lang="filter.1m">1 Bulan Terakhir</option>
                <option value="1y" data-lang="filter.1y">1 Tahun Terakhir</option>
            </select>
            <button type="submit" class="btn-primary !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-filter"></i> <span data-lang="common.filter">Filter</span></button>
            @if(request()->anyFilled(['search','dari','sampai']))<a href="{{ route('barang-keluar.index') }}" class="btn-outline !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-times"></i> <span data-lang="common.reset">Reset</span></a>@endif
        </form>
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('barang-keluar.create') }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-plus"></i> Input Keluar</a>
            <a href="{{ route('export.barang-keluar.pdf', ['dari'=>request('dari',now()->startOfMonth()->format('Y-m-d')),'sampai'=>request('sampai',now()->format('Y-m-d'))]) }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-pdf"></i> PDF</a>
            <a href="{{ route('export.barang-keluar.excel', ['dari'=>request('dari',now()->startOfMonth()->format('Y-m-d')),'sampai'=>request('sampai',now()->format('Y-m-d'))]) }}" class="btn-success !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-excel"></i> Excel</a>
        </div>
    </div>

    <div class="sm:hidden space-y-3">
        @forelse($transaksis as $trx)
        <a href="{{ route('barang-keluar.show', $trx->id) }}" class="glass rounded-xl p-4 block">
            <div class="flex items-start justify-between mb-2">
                <div><p class="font-medium text-sm">{{ $trx->barang->nama }}</p><p class="text-xs font-mono text-rose-600 dark:text-rose-400">{{ $trx->kode_transaksi }}</p></div>
                <span class="text-xs" style="color:var(--text-muted)">{{ $trx->tanggal->format('d/m/Y') }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span style="color:var(--text-muted)">{{ $trx->tujuan ?? '-' }}</span>
                <span class="text-rose-600 dark:text-rose-400 font-bold">-{{ number_format($trx->jumlah) }} &rarr; HPP: Rp {{ number_format($trx->total_harga,0,',','.') }}</span>
            </div>
        </a>
        @empty
        <div class="glass rounded-xl p-8 text-center" style="color:var(--text-muted)"><i class="fas fa-inbox text-3xl mb-2 opacity-30"></i><p class="text-sm">Tidak ada data.</p></div>
        @endforelse
    </div>

    <div class="glass rounded-2xl overflow-hidden hidden sm:block">
        <div class="table-responsive">
            <table class="w-full text-sm text-left">
                <thead><tr style="background:var(--bg-input)">
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Tanggal</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Kode TRX</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Barang</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider hidden lg:table-cell" style="color:var(--text-muted)">Tujuan</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)">Jumlah</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)">Total HPP</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-center" style="color:var(--text-muted)">Aksi</th>
                </tr></thead>
                <tbody>
                    @forelse($transaksis as $trx)
                    <tr class="border-t transition-colors hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]" style="border-color:var(--border-color)">
                        <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $trx->tanggal->format('d M Y') }}</td>
                        <td class="px-5 py-3 font-mono text-xs text-rose-600 dark:text-rose-400">{{ $trx->kode_transaksi }}</td>
                        <td class="px-5 py-3"><p class="font-medium">{{ $trx->barang->nama }}</p><p class="text-[10px]" style="color:var(--text-muted)">{{ $trx->barang->kode }}</p></td>
                        <td class="px-5 py-3 hidden lg:table-cell" style="color:var(--text-secondary)">{{ $trx->tujuan ?? '-' }}</td>
                        <td class="px-5 py-3 text-right"><span class="font-bold">{{ number_format($trx->jumlah) }}</span> <span class="text-xs" style="color:var(--text-muted)">{{ $trx->barang->satuan }}</span></td>
                        <td class="px-5 py-3 text-right" style="color:var(--text-secondary)">Rp {{ number_format($trx->total_harga,0,',','.') }}</td>
                        <td class="px-5 py-3 text-center"><a href="{{ route('barang-keluar.show', $trx->id) }}" class="p-1.5 rounded-lg hover:bg-indigo-500/10 text-indigo-500 transition-colors inline-block"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center" style="color:var(--text-muted)"><i class="fas fa-inbox text-4xl mb-3 opacity-20"></i><p>Tidak ada data.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t" style="border-color:var(--border-color)">{{ $transaksis->links() }}</div>
    </div>
</div>
@endsection
