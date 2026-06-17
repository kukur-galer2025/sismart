@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('barang.index') }}" class="flex items-center gap-2 transition-colors text-sm" style="color:var(--text-muted)">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Barang
    </a>

    <div class="glass rounded-2xl p-5 sm:p-8" x-data="{ stokAwal: {{ old('stok_awal', 0) }} }">
        <form action="{{ route('barang.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-base font-semibold border-b pb-2 flex items-center gap-2" style="border-color:var(--border-color)">
                        <i class="fas fa-info-circle text-indigo-500"></i> Informasi Dasar
                    </h3>
                    <div>
                        <label class="form-label"><i class="fas fa-barcode text-indigo-500"></i> Kode Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode" value="{{ old('kode') }}" required class="form-input" placeholder="Contoh: BRG001">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-box text-indigo-500"></i> Nama Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="form-input" placeholder="Nama barang">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-tags text-indigo-500"></i> Kategori</label>
                        <select name="kategori_id" class="form-input">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $k)<option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-ruler text-indigo-500"></i> Satuan <span class="text-rose-500">*</span></label>
                        <input type="text" name="satuan" value="{{ old('satuan', 'pcs') }}" required class="form-input" placeholder="pcs, box, lusin">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-map-marker-alt text-indigo-500"></i> Lokasi Penyimpanan</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="form-input" placeholder="Rak A1, Gudang Utama">
                    </div>
                    
                    <div class="p-4 rounded-xl border mt-2" style="background:var(--bg-sidebar); border-color:var(--border-color)">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-box-open text-emerald-500"></i>
                            <span class="font-semibold text-sm">Input Stok Awal (Opsional)</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-xs">Jumlah Stok</label>
                                <input type="number" name="stok_awal" x-model.number="stokAwal" min="0" class="form-input text-sm">
                            </div>
                            <div x-show="stokAwal > 0" x-transition>
                                <label class="form-label text-xs">Harga Modal (HPP) / Satuan</label>
                                <input type="number" name="harga_modal_awal" value="{{ old('harga_modal_awal') }}" min="0" class="form-input text-sm" :required="stokAwal > 0">
                            </div>
                        </div>
                        <p class="text-[10px] mt-2 text-emerald-600 dark:text-emerald-400" x-show="stokAwal > 0"><i class="fas fa-info-circle"></i> Stok awal akan otomatis dicatat sebagai transaksi Barang Masuk pertama.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-base font-semibold border-b pb-2 flex items-center gap-2" style="border-color:var(--border-color)">
                        <i class="fas fa-sliders text-indigo-500"></i> Pengaturan Stok
                    </h3>
                    <div>
                        <label class="form-label"><i class="fas fa-cog text-indigo-500"></i> Metode Penilaian <span class="text-rose-500">*</span></label>
                        <select name="metode_stok" required class="form-input">
                            <option value="average" {{ old('metode_stok') == 'average' ? 'selected' : '' }}>Average (Rata-rata)</option>
                            <option value="fifo" {{ old('metode_stok') == 'fifo' ? 'selected' : '' }}>FIFO (First In First Out)</option>
                        </select>
                        <p class="text-[11px] mt-1" style="color:var(--text-muted)"><i class="fas fa-info-circle mr-1"></i>Menentukan perhitungan HPP saat barang keluar.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label"><i class="fas fa-shield-halved text-indigo-500"></i> Safety Stock <span class="text-rose-500">*</span></label>
                            <input type="number" name="safety_stock" value="{{ old('safety_stock', 0) }}" required min="0" class="form-input">
                        </div>
                        <div>
                            <label class="form-label"><i class="fas fa-clock text-indigo-500"></i> Lead Time <span class="text-rose-500">*</span></label>
                            <input type="number" name="lead_time" value="{{ old('lead_time', 1) }}" required min="1" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label"><i class="fas fa-chart-line text-indigo-500"></i> Pakai Rata²/Hari <span class="text-rose-500">*</span></label>
                            <input type="number" name="pemakaian_rata_rata" value="{{ old('pemakaian_rata_rata', 0) }}" required min="0" class="form-input">
                        </div>
                        <div>
                            <label class="form-label"><i class="fas fa-arrow-up text-indigo-500"></i> Pakai Maks/Hari <span class="text-rose-500">*</span></label>
                            <input type="number" name="pemakaian_maksimum" value="{{ old('pemakaian_maksimum', 0) }}" required min="0" class="form-input">
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-indigo-500/10 border border-indigo-500/20">
                        <p class="text-xs text-indigo-700 dark:text-indigo-300"><i class="fas fa-lightbulb mr-1"></i> Data di atas digunakan untuk menghitung Safety Stock & Reorder Point otomatis.</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color:var(--border-color)">
                <label class="form-label"><i class="fas fa-sticky-note text-indigo-500"></i> Keterangan Tambahan</label>
                <textarea name="keterangan" rows="3" class="form-input">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4">
                <button type="reset" class="btn-outline w-full sm:w-auto justify-center"><i class="fas fa-undo"></i> Reset</button>
                <button type="submit" class="btn-primary w-full sm:w-auto justify-center"><i class="fas fa-save"></i> Simpan Barang</button>
            </div>
        </form>
    </div>
</div>
@endsection
