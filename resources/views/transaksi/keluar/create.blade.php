@extends('layouts.app')
@section('title', 'Input Barang Keluar')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('barang-keluar.index') }}" class="flex items-center gap-2 transition-colors text-sm" style="color:var(--text-muted)"><i class="fas fa-arrow-left"></i> Kembali</a>

    <div class="glass rounded-2xl p-5 sm:p-8" x-data="{
        sel: null, jumlah: 1,
        list: @js($barangs->map(fn($b) => ['id'=>$b->id,'nama'=>$b->nama,'kode'=>$b->kode,'stok'=>$b->stok,'satuan'=>$b->satuan,'harga_rata_rata'=>$b->harga_rata_rata,'metode_stok'=>$b->metode_stok])),
        pick(id) { this.sel = this.list.find(b => b.id == id) || null },
        get est() { return this.sel ? this.jumlah * this.sel.harga_rata_rata : 0 },
        harga_jual: null,
        get total_jual() { return (this.harga_jual || 0) * this.jumlah },
        get ok() { return !this.sel || this.jumlah <= this.sel.stok },
        fmt(v) { return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(v) }
    }">
        <form action="{{ route('barang-keluar.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-rose-500/10 border border-rose-500/20 rounded-xl p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0"><i class="fas fa-arrow-up text-lg"></i></div>
                <div>
                    <h3 class="text-rose-700 dark:text-rose-400 font-semibold text-sm">Pencatatan Barang Keluar</h3>
                    <p class="text-xs text-rose-600/70 dark:text-rose-400/60">Validasi stok otomatis. HPP dihitung FIFO/Average.</p>
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
                        <select name="barang_id" required @change="pick($event.target.value)" class="form-input">
                            <option value="">Pilih Barang...</option>
                            @foreach($barangs as $b)<option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>{{ $b->kode }} - {{ $b->nama }} (Stok: {{ $b->stok }})</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-building text-indigo-500"></i> Tujuan / Pembeli</label>
                        <input type="text" name="tujuan" value="{{ old('tujuan') }}" placeholder="Contoh: Dept. IT atau Pelanggan A" class="form-input">
                    </div>
                    <div class="p-4 rounded-xl border mt-2" style="background:var(--bg-sidebar); border-color:var(--border-color)">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-money-bill-wave text-emerald-500"></i>
                            <span class="font-semibold text-sm">Penjualan (Opsional)</span>
                        </div>
                        <div>
                            <label class="form-label text-xs">Harga Jual Satuan (Ke Pelanggan)</label>
                            <input type="number" name="harga_jual_satuan" x-model.number="harga_jual" min="0" class="form-input text-sm" placeholder="Kosongi jika bukan penjualan">
                            <p class="text-[10px] mt-1" style="color:var(--text-muted)"><i class="fas fa-info-circle"></i> Jika diisi, otomatis tercatat di Laba Rugi.</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div x-show="sel" x-transition class="p-4 rounded-xl border space-y-2" style="background:var(--bg-input); border-color:var(--border-color)">
                        <div class="flex justify-between text-sm"><span style="color:var(--text-muted)"><i class="fas fa-boxes-stacked mr-1"></i> Stok</span><span class="font-bold" x-text="sel?.stok+' '+sel?.satuan"></span></div>
                        <div class="flex justify-between text-sm"><span style="color:var(--text-muted)"><i class="fas fa-tag mr-1"></i> Harga Rata²</span><span x-text="fmt(sel?.harga_rata_rata||0)"></span></div>
                        <div class="flex justify-between text-sm"><span style="color:var(--text-muted)"><i class="fas fa-cog mr-1"></i> Metode</span><span class="text-xs uppercase font-bold tracking-wider" :class="sel?.metode_stok==='fifo'?'text-cyan-600 dark:text-cyan-400':'text-fuchsia-600 dark:text-fuchsia-400'" x-text="sel?.metode_stok"></span></div>
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-hashtag text-indigo-500"></i> Jumlah Keluar <span class="text-rose-500">*</span></label>
                        <input type="number" name="jumlah" x-model.number="jumlah" required min="1" :max="sel?.stok" class="form-input" :class="!ok && 'border-rose-500 focus:border-rose-500'">
                        <p x-show="!ok" class="text-xs text-rose-600 dark:text-rose-400 mt-1"><i class="fas fa-exclamation-triangle"></i> Melebihi stok!</p>
                    </div>
                    <div class="pt-4 mt-4 border-t" style="border-color:var(--border-color)">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs mb-1" style="color:var(--text-muted)"><i class="fas fa-calculator mr-1"></i> Estimasi HPP</p>
                                <p class="text-xl font-bold text-rose-600 dark:text-rose-400" x-text="fmt(est)">Rp 0</p>
                                <p class="text-[9px] mt-1" style="color:var(--text-muted)">*Bisa beda jika FIFO</p>
                            </div>
                            <div x-show="harga_jual > 0" x-transition>
                                <p class="text-xs mb-1" style="color:var(--text-muted)"><i class="fas fa-cash-register mr-1"></i> Total Penjualan</p>
                                <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400" x-text="fmt(total_jual)">Rp 0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <label class="form-label"><i class="fas fa-sticky-note text-indigo-500"></i> Keterangan</label>
                <textarea name="keterangan" rows="2" class="form-input">{{ old('keterangan') }}</textarea>
            </div>
            <div class="flex justify-end pt-4 border-t" style="border-color:var(--border-color)">
                <button type="submit" :disabled="!ok" class="btn-danger w-full sm:w-auto justify-center disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-check"></i> Proses Barang Keluar</button>
            </div>
        </form>
    </div>
</div>
@endsection
