@extends('layouts.app')
@section('title', 'Jurnal Umum')
@section('content')
<div class="space-y-6">
    <div class="space-y-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold" data-lang="jurnal.title">Jurnal Umum</h2>
            <p class="text-xs sm:text-sm" style="color:var(--text-muted)" data-lang="jurnal.subtitle">Catatan seluruh transaksi keuangan persediaan</p>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
            <form method="GET" action="{{ route('keuangan.jurnal') }}" class="flex items-center gap-1.5 flex-wrap">
                <input type="date" name="dari" value="{{ request('dari') }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <span class="text-[10px]" style="color:var(--text-muted)" data-lang="common.sd">s/d</span>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <button type="submit" class="btn-primary !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-filter"></i> <span data-lang="common.filter">Filter</span></button>
            </form>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('export.jurnal.pdf', ['dari'=>request('dari',now()->startOfMonth()->format('Y-m-d')),'sampai'=>request('sampai',now()->format('Y-m-d'))]) }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-pdf"></i> PDF</a>
                <a href="{{ route('export.jurnal.excel', ['dari'=>request('dari',now()->startOfMonth()->format('Y-m-d')),'sampai'=>request('sampai',now()->format('Y-m-d'))]) }}" class="btn-success !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-excel"></i> Excel</a>
            </div>
        </div>
    </div>

    <div class="sm:hidden space-y-3">
        @forelse($jurnals as $j)
        <div class="glass rounded-xl p-4">
            <div class="flex items-start justify-between mb-2">
                <p class="text-xs font-mono text-indigo-600 dark:text-indigo-400">{{ $j->kode_jurnal }}</p>
                <p class="text-xs" style="color:var(--text-muted)">{{ $j->tanggal->format('d/m/Y') }}</p>
            </div>
            <p class="text-sm font-medium mb-1">{{ $j->akun->nama ?? '-' }}</p>
            <p class="text-xs truncate mb-2" style="color:var(--text-muted)">{{ $j->keterangan }}</p>
            <div class="flex justify-between text-xs">
                <span class="text-emerald-600 dark:text-emerald-400 font-medium">D: {{ $j->debit > 0 ? 'Rp '.number_format($j->debit,0,',','.') : '-' }}</span>
                <span class="text-rose-600 dark:text-rose-400 font-medium">K: {{ $j->kredit > 0 ? 'Rp '.number_format($j->kredit,0,',','.') : '-' }}</span>
            </div>
        </div>
        @empty
        <div class="glass rounded-xl p-8 text-center" style="color:var(--text-muted)"><i class="fas fa-book-open text-3xl mb-2 opacity-30"></i><p class="text-sm">Belum ada entri jurnal.</p></div>
        @endforelse
    </div>

    <div class="glass rounded-2xl overflow-hidden hidden sm:block">
        <div class="table-responsive">
            <table class="w-full text-sm text-left">
                <thead><tr style="background:var(--bg-input)">
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Tanggal</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Kode Jurnal</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Akun</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider hidden lg:table-cell" style="color:var(--text-muted)">Keterangan</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)">Debit</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)">Kredit</th>
                </tr></thead>
                <tbody>
                    @forelse($jurnals as $j)
                    <tr class="border-t transition-colors hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]" style="border-color:var(--border-color)">
                        <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $j->tanggal->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 font-mono text-xs text-indigo-600 dark:text-indigo-400">{{ $j->kode_jurnal }}</td>
                        <td class="px-5 py-3"><span class="font-medium">{{ $j->akun->nama ?? '-' }}</span> <span class="text-[10px]" style="color:var(--text-muted)">({{ $j->akun->kode ?? '' }})</span></td>
                        <td class="px-5 py-3 max-w-xs truncate hidden lg:table-cell" style="color:var(--text-secondary)">{{ $j->keterangan }}</td>
                        <td class="px-5 py-3 text-right {{ $j->debit > 0 ? 'text-emerald-600 dark:text-emerald-400 font-medium' : '' }}" style="{{ $j->debit == 0 ? 'color:var(--text-muted)' : '' }}">{{ $j->debit > 0 ? 'Rp '.number_format($j->debit,0,',','.') : '-' }}</td>
                        <td class="px-5 py-3 text-right {{ $j->kredit > 0 ? 'text-rose-600 dark:text-rose-400 font-medium' : '' }}" style="{{ $j->kredit == 0 ? 'color:var(--text-muted)' : '' }}">{{ $j->kredit > 0 ? 'Rp '.number_format($j->kredit,0,',','.') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center" style="color:var(--text-muted)"><i class="fas fa-book-open text-4xl mb-3 opacity-20"></i><p>Belum ada entri jurnal.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t" style="border-color:var(--border-color)">{{ $jurnals->links() }}</div>
    </div>
</div>
@endsection
