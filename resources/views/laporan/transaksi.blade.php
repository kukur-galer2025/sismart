@extends('layouts.app')
@section('title', 'Laporan Transaksi')
@section('content')
<div class="space-y-6">
    <div class="space-y-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold" data-lang="lap_trx.heading">Laporan Transaksi</h2>
            <p class="text-xs sm:text-sm" style="color:var(--text-muted)">{{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
            <form method="GET" action="{{ route('laporan.transaksi') }}" class="flex items-center gap-1.5 flex-wrap">
                <input type="date" name="dari" value="{{ $dari }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <span class="text-[10px]" style="color:var(--text-muted)" data-lang="common.sd">s/d</span>
                <input type="date" name="sampai" value="{{ $sampai }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <select onchange="if(this.value){ let d=new Date(), s=new Date(); if(this.value==='7d')s.setDate(d.getDate()-7); if(this.value==='1m')s.setMonth(d.getMonth()-1); if(this.value==='1y')s.setFullYear(d.getFullYear()-1); let f=this.closest('form'); f.dari.value=s.toISOString().split('T')[0]; f.sampai.value=d.toISOString().split('T')[0]; f.submit(); }" class="form-input !w-auto !py-1.5 !text-[11px] font-medium cursor-pointer" style="color:var(--text-secondary)">
                    <option value="" data-lang="filter.preset">Pilih Periode...</option>
                    <option value="7d" data-lang="filter.7d">7 Hari Terakhir</option>
                    <option value="1m" data-lang="filter.1m">1 Bulan Terakhir</option>
                    <option value="1y" data-lang="filter.1y">1 Tahun Terakhir</option>
                </select>
                <button type="submit" class="btn-primary !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-filter"></i> <span data-lang="common.filter">Filter</span></button>
            </form>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('export.transaksi.pdf', ['dari'=>$dari,'sampai'=>$sampai]) }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-pdf"></i> PDF</a>
                <a href="{{ route('export.transaksi.excel', ['dari'=>$dari,'sampai'=>$sampai]) }}" class="btn-success !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-file-excel"></i> Excel</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @php $statItems = [
            ['lap_trx.total_masuk','Total Masuk','emerald','arrow-down',$totalMasuk,$masuk->count()],
            ['lap_trx.total_keluar','Total Keluar','rose','arrow-up',$totalKeluar,$keluar->count()],
            ['lap_trx.selisih','Selisih','indigo','chart-bar',$totalMasuk-$totalKeluar,null]
        ]; @endphp
        @foreach($statItems as [$langKey,$lbl,$clr,$ico,$val,$cnt])
        <div class="glass p-4 rounded-2xl stat-card border-l-4 border-{{ $clr }}-500">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba({{ $clr==='emerald'?'16,185,129':($clr==='rose'?'239,68,68':'99,102,241') }},0.12)"><i class="fas fa-{{ $ico }} text-{{ $clr }}-500"></i></div>
                <div>
                    <p class="text-xs" style="color:var(--text-muted)" data-lang="{{ $langKey }}">{{ $lbl }}</p>
                    <p class="text-lg font-bold {{ $lbl==='Selisih'&&$val<0?'text-rose-600 dark:text-rose-400':'text-'.$clr.'-600 dark:text-'.$clr.'-400' }}">Rp {{ number_format($val,0,',','.') }}</p>
                    @if($cnt!==null)<p class="text-[10px]" style="color:var(--text-muted)">{{ $cnt }} transaksi</p>@endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div x-data="{ tab:'masuk' }">
        <div class="flex border-b mb-4" style="border-color:var(--border-color)">
            <button @click="tab='masuk'" :class="tab==='masuk'?'text-emerald-600 dark:text-emerald-400 border-b-2 border-emerald-500':''" class="px-5 py-3 text-sm font-medium transition-colors" :style="tab!=='masuk'?'color:var(--text-muted)':''"><i class="fas fa-arrow-down mr-1"></i>Masuk ({{ $masuk->count() }})</button>
            <button @click="tab='keluar'" :class="tab==='keluar'?'text-rose-600 dark:text-rose-400 border-b-2 border-rose-500':''" class="px-5 py-3 text-sm font-medium transition-colors" :style="tab!=='keluar'?'color:var(--text-muted)':''"><i class="fas fa-arrow-up mr-1"></i>Keluar ({{ $keluar->count() }})</button>
        </div>
        @foreach(['masuk'=>[$masuk,'Supplier','supplier','emerald',$totalMasuk],'keluar'=>[$keluar,'Tujuan','tujuan','rose',$totalKeluar]] as $type=>[$data,$col4,$field,$color,$total])
        <div x-show="tab==='{{ $type }}'" {{ $type==='keluar'?'x-cloak':'' }} class="glass rounded-2xl overflow-hidden">
            <div class="table-responsive">
                <table class="w-full text-sm text-left">
                    <thead><tr style="background:var(--bg-input)">
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">No</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Tanggal</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Kode</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Barang</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider hidden md:table-cell" style="color:var(--text-muted)">{{ $col4 }}</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)">Jumlah</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)">Total</th>
                    </tr></thead>
                    <tbody>
                        @forelse($data as $i=>$trx)
                        <tr class="border-t transition-colors hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]" style="border-color:var(--border-color)">
                            <td class="px-4 py-3" style="color:var(--text-muted)">{{ $i+1 }}</td>
                            <td class="px-4 py-3">{{ $trx->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ $trx->kode_transaksi }}</td>
                            <td class="px-4 py-3 font-medium">{{ $trx->barang->nama }}</td>
                            <td class="px-4 py-3 hidden md:table-cell" style="color:var(--text-secondary)">{{ $trx->$field ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-medium">{{ number_format($trx->jumlah) }}</td>
                            <td class="px-4 py-3 text-right text-{{ $color }}-600 dark:text-{{ $color }}-400 font-medium">Rp {{ number_format($trx->total_harga,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center" style="color:var(--text-muted)">Tidak ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                    @if($data->count())
                    <tfoot><tr style="background:var(--bg-input)"><td colspan="6" class="px-4 py-3 text-right font-semibold" style="color:var(--text-secondary)">Total</td><td class="px-4 py-3 text-right font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">Rp {{ number_format($total,0,',','.') }}</td></tr></tfoot>
                    @endif
                </table>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
