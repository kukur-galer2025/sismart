@extends('layouts.app')
@section('title', 'Laporan Perubahan Modal')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="space-y-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold">Laporan Perubahan Modal</h2>
            <p class="text-xs sm:text-sm" style="color:var(--text-muted)">{{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} — {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
            <form method="GET" action="{{ route('keuangan.perubahan-modal') }}" class="flex items-center gap-1.5 flex-wrap">
                <input type="date" name="dari" value="{{ $dari }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <span class="text-[10px]" style="color:var(--text-muted)">s/d</span>
                <input type="date" name="sampai" value="{{ $sampai }}" class="form-input !w-[120px] !py-1.5 !text-[11px]">
                <select onchange="if(this.value){ let d=new Date(), s=new Date(); if(this.value==='7d')s.setDate(d.getDate()-7); if(this.value==='1m')s.setMonth(d.getMonth()-1); if(this.value==='1y')s.setFullYear(d.getFullYear()-1); let f=this.closest('form'); f.dari.value=s.toISOString().split('T')[0]; f.sampai.value=d.toISOString().split('T')[0]; f.submit(); }" class="form-input !w-auto !py-1.5 !text-[11px] font-medium cursor-pointer" style="color:var(--text-secondary)">
                    <option value="">Pilih Periode...</option>
                    <option value="7d">7 Hari Terakhir</option>
                    <option value="1m">1 Bulan Terakhir</option>
                    <option value="1y">1 Tahun Terakhir</option>
                </select>
                <button type="submit" class="btn-primary !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-filter"></i> Filter</button>
            </form>
            <div class="flex items-center gap-1.5">
                <a href="#" class="btn-danger !text-[11px] !py-1.5 !px-2.5" onclick="alert('Export PDF belum tersedia (Coming Soon)')"><i class="fas fa-file-pdf"></i> PDF</a>
                <a href="#" class="btn-success !text-[11px] !py-1.5 !px-2.5" onclick="alert('Export Excel belum tersedia (Coming Soon)')"><i class="fas fa-file-excel"></i> Excel</a>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-6 sm:p-10 no-print" id="print-area">
        <div class="text-center mb-8">
            <h3 class="text-lg font-bold">Kedana Kedini — Laporan Perubahan Modal</h3>
            <p class="text-sm" style="color:var(--text-muted)">Periode: {{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} — {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</p>
        </div>

        <div class="w-full max-w-2xl mx-auto border rounded-2xl overflow-hidden" style="border-color:var(--border-color); background:var(--bg-card)">
            <table class="w-full text-sm">
                <tbody>
                    <tr class="border-b" style="border-color:var(--border-color)">
                        <td class="py-4 px-5 font-semibold" style="color:var(--text-secondary)">Modal Awal <span class="text-xs font-normal opacity-70">({{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }})</span></td>
                        <td class="py-4 px-5 text-right font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($modalAwal, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-b" style="border-color:var(--border-color); background:var(--bg-input)">
                        <td class="py-4 px-5" style="color:var(--text-secondary)">Laba / (Rugi) Bersih</td>
                        <td class="py-4 px-5 text-right font-medium {{ $labaBersih < 0 ? 'text-rose-500' : 'text-emerald-500' }}">Rp {{ number_format($labaBersih, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-b" style="border-color:var(--border-color)">
                        <td class="py-4 px-5" style="color:var(--text-secondary)">Prive (Penarikan)</td>
                        <td class="py-4 px-5 text-right font-medium text-rose-500">(Rp {{ number_format($prive, 0, ',', '.') }})</td>
                    </tr>
                    <tr style="background:var(--hover-bg)">
                        <td class="py-4 px-5 font-bold text-base">Modal Akhir <span class="text-xs font-normal opacity-70">({{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }})</span></td>
                        <td class="py-4 px-5 text-right font-bold text-base">Rp {{ number_format($modalAkhir, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
