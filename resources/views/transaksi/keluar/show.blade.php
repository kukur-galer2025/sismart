@extends('layouts.app')
@section('title', 'Detail Transaksi Keluar')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('barang-keluar.index') }}" class="flex items-center gap-2 transition-colors text-sm" style="color:var(--text-muted)"><i class="fas fa-arrow-left"></i> Kembali</a>

    <div class="glass rounded-2xl p-5 sm:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg sm:text-xl font-bold">{{ $barangKeluar->kode_transaksi }}</h2>
                <p class="text-sm" style="color:var(--text-muted)">{{ $barangKeluar->tanggal->translatedFormat('d F Y') }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-500/15 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0"><i class="fas fa-arrow-up text-lg"></i></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <p class="text-[11px] uppercase tracking-wider mb-0.5" style="color:var(--text-muted)">Nama Barang</p>
                    <p class="text-sm font-medium">{{ $barangKeluar->barang->nama }}</p>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 font-mono">{{ $barangKeluar->barang->kode }}</p>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-wider mb-0.5" style="color:var(--text-muted)">Tujuan</p>
                    <p class="text-sm" style="color:var(--text-secondary)">{{ $barangKeluar->tujuan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-wider mb-0.5" style="color:var(--text-muted)">Dicatat oleh</p>
                    <p class="text-sm" style="color:var(--text-secondary)">{{ $barangKeluar->user->name }}</p>
                </div>
                @if($barangKeluar->keterangan)
                <div>
                    <p class="text-[11px] uppercase tracking-wider mb-0.5" style="color:var(--text-muted)">Keterangan</p>
                    <p class="text-sm p-3 rounded-xl border" style="color:var(--text-secondary); background:var(--bg-input); border-color:var(--border-color)">{{ $barangKeluar->keterangan }}</p>
                </div>
                @endif
            </div>
            <div class="bg-rose-500/5 border border-rose-500/20 rounded-xl p-5 space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-rose-500/10">
                    <span class="text-sm" style="color:var(--text-muted)">Jumlah Keluar</span>
                    <span class="text-lg font-bold">{{ number_format($barangKeluar->jumlah) }} {{ $barangKeluar->barang->satuan }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-rose-500/10">
                    <span class="text-sm" style="color:var(--text-muted)">Harga Satuan (HPP)</span>
                    <span class="text-sm" style="color:var(--text-secondary)">Rp {{ number_format($barangKeluar->harga_satuan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-medium text-rose-700 dark:text-rose-300">Total Nilai HPP</span>
                    <span class="text-2xl font-bold text-rose-600 dark:text-rose-400">Rp {{ number_format($barangKeluar->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
