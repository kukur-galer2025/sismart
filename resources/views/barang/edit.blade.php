@extends('layouts.app')
@section('title', 'Edit Barang')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('barang.index') }}" class="flex items-center gap-2 transition-colors text-sm" style="color:var(--text-muted)">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <span class="px-3 py-1 rounded-full text-xs border" style="color:var(--text-muted); border-color:var(--border-color)">ID: {{ $barang->id }}</span>
    </div>

    <div class="glass rounded-2xl p-5 sm:p-8">
        <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-base font-semibold border-b pb-2 flex items-center gap-2" style="border-color:var(--border-color)">
                        <i class="fas fa-info-circle text-indigo-500"></i> Informasi Dasar
                    </h3>
                    <div>
                        <label class="form-label"><i class="fas fa-barcode text-indigo-500"></i> Kode Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode" value="{{ old('kode', $barang->kode) }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-box text-indigo-500"></i> Nama Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $barang->nama) }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-tags text-indigo-500"></i> Kategori</label>
                        <select name="kategori_id" class="form-input">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $k)<option value="{{ $k->id }}" {{ old('kategori_id', $barang->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>@endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label"><i class="fas fa-ruler text-indigo-500"></i> Satuan <span class="text-rose-500">*</span></label>
                            <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label"><i class="fas fa-toggle-on text-indigo-500"></i> Status</label>
                            <select name="is_active" class="form-input">
                                <option value="1" {{ old('is_active', $barang->is_active) ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $barang->is_active) ? '' : 'selected' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-map-marker-alt text-indigo-500"></i> Lokasi</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $barang->lokasi) }}" class="form-input">
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-base font-semibold border-b pb-2 flex items-center gap-2" style="border-color:var(--border-color)">
                        <i class="fas fa-sliders text-indigo-500"></i> Pengaturan Stok
                    </h3>
                    <div>
                        <label class="form-label"><i class="fas fa-cog text-indigo-500"></i> Metode Penilaian <span class="text-rose-500">*</span></label>
                        <select name="metode_stok" required class="form-input">
                            <option value="average" {{ old('metode_stok', $barang->metode_stok) == 'average' ? 'selected' : '' }}>Average (Rata-rata)</option>
                            <option value="fifo" {{ old('metode_stok', $barang->metode_stok) == 'fifo' ? 'selected' : '' }}>FIFO (First In First Out)</option>
                        </select>
                        @if($barang->stok > 0)
                            <p class="text-[11px] text-amber-600 dark:text-amber-400 mt-1"><i class="fas fa-exclamation-triangle"></i> Hati-hati: mengubah metode saat stok ada dapat mempengaruhi HPP.</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label"><i class="fas fa-shield-halved text-indigo-500"></i> Safety Stock <span class="text-rose-500">*</span></label>
                            <input type="number" name="safety_stock" value="{{ old('safety_stock', $barang->safety_stock) }}" required min="0" class="form-input">
                        </div>
                        <div>
                            <label class="form-label"><i class="fas fa-clock text-indigo-500"></i> Lead Time <span class="text-rose-500">*</span></label>
                            <input type="number" name="lead_time" value="{{ old('lead_time', $barang->lead_time) }}" required min="1" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label"><i class="fas fa-chart-line text-indigo-500"></i> Pakai Rata²/Hari <span class="text-rose-500">*</span></label>
                            <input type="number" name="pemakaian_rata_rata" value="{{ old('pemakaian_rata_rata', $barang->pemakaian_rata_rata) }}" required min="0" class="form-input">
                        </div>
                        <div>
                            <label class="form-label"><i class="fas fa-arrow-up text-indigo-500"></i> Pakai Maks/Hari <span class="text-rose-500">*</span></label>
                            <input type="number" name="pemakaian_maksimum" value="{{ old('pemakaian_maksimum', $barang->pemakaian_maksimum) }}" required min="0" class="form-input">
                        </div>
                    </div>
                    <div class="p-4 rounded-xl border" style="background:var(--bg-input); border-color:var(--border-color)">
                        <p class="text-xs mb-2 font-medium" style="color:var(--text-muted)">Perhitungan Sistem:</p>
                        <div class="flex justify-between text-sm py-1">
                            <span style="color:var(--text-secondary)">Safety Stock Ideal</span>
                            <span class="font-mono font-medium">{{ ($barang->pemakaian_maksimum - $barang->pemakaian_rata_rata) * $barang->lead_time }}</span>
                        </div>
                        <div class="flex justify-between text-sm py-1">
                            <span style="color:var(--text-secondary)">Reorder Point</span>
                            <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ ($barang->pemakaian_rata_rata * $barang->lead_time) + $barang->safety_stock }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color:var(--border-color)">
                <label class="form-label"><i class="fas fa-sticky-note text-indigo-500"></i> Keterangan</label>
                <textarea name="keterangan" rows="3" class="form-input">{{ old('keterangan', $barang->keterangan) }}</textarea>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4">
                <a href="{{ route('barang.index') }}" class="btn-outline w-full sm:w-auto justify-center">Batal</a>
                <button type="submit" class="btn-primary w-full sm:w-auto justify-center"><i class="fas fa-save"></i> Update Barang</button>
            </div>
        </form>
    </div>
</div>
@endsection
