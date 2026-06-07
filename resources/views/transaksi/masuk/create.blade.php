@extends('layouts.app')
@section('title', 'Input Barang Masuk')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('barang-masuk.index') }}" class="flex items-center gap-2 transition-colors text-sm" style="color:var(--text-muted)"><i class="fas fa-arrow-left"></i> Kembali</a>

    <div class="glass rounded-2xl p-5 sm:p-8" x-data="{ hargaSatuan:0, jumlah:1, get total(){ return this.hargaSatuan*this.jumlah }, fmt(v){ return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(v) } }">
        <form action="{{ route('barang-masuk.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0"><i class="fas fa-arrow-down text-lg"></i></div>
                <div>
                    <h3 class="text-emerald-700 dark:text-emerald-400 font-semibold text-sm">Pencatatan Barang Masuk</h3>
                    <p class="text-xs text-emerald-600/70 dark:text-emerald-400/60">Stok & jurnal persediaan terupdate otomatis.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="form-label"><i class="fas fa-calendar-day text-indigo-500"></i> Tanggal <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-cube text-indigo-500"></i> Barang <span class="text-rose-500">*</span></label>
                        <select name="barang_id" required class="form-input">
                            <option value="">Pilih Barang...</option>
                            @foreach($barangs as $b)<option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>{{ $b->kode }} - {{ $b->nama }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-truck text-indigo-500"></i> Supplier</label>
                        <input type="text" name="supplier" value="{{ old('supplier') }}" placeholder="Nama supplier..." class="form-input">
                    </div>
                </div>
                <div class="space-y-4 p-5 rounded-xl border" style="background:var(--bg-input); border-color:var(--border-color)">
                    <div>
                        <label class="form-label"><i class="fas fa-hashtag text-indigo-500"></i> Jumlah Masuk <span class="text-rose-500">*</span></label>
                        <input type="number" name="jumlah" x-model.number="jumlah" required min="1" class="form-input">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-tag text-indigo-500"></i> Harga Beli / HPP Satuan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="harga_satuan" x-model.number="hargaSatuan" required min="0" step="0.01" class="form-input">
                    </div>
                    <div class="pt-4 mt-4 border-t" style="border-color:var(--border-color)">
                        <p class="text-xs mb-1" style="color:var(--text-muted)"><i class="fas fa-calculator mr-1"></i> Total Nilai</p>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400" x-text="fmt(total)">Rp 0</p>
                    </div>
                </div>
            </div>
            <div>
                <label class="form-label"><i class="fas fa-sticky-note text-indigo-500"></i> Keterangan</label>
                <textarea name="keterangan" rows="2" class="form-input">{{ old('keterangan') }}</textarea>
            </div>
            <div class="flex justify-end pt-4 border-t" style="border-color:var(--border-color)">
                <button type="submit" class="btn-success w-full sm:w-auto justify-center"><i class="fas fa-check"></i> Proses Barang Masuk</button>
            </div>
        </form>
    </div>
</div>
@endsection
