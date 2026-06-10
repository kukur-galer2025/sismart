@extends('layouts.app')
@section('title', 'Manual Book & Rumus Sistem')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    {{-- Header --}}
    <div class="glass rounded-3xl p-8 text-center relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <h2 class="text-2xl md:text-3xl font-extrabold mb-3 flex items-center justify-center gap-3" style="color:var(--text-primary)">
            <i class="fas fa-book-open text-indigo-500"></i> Dokumentasi Rumus & Aturan Sistem
        </h2>
        <p class="text-sm md:text-base max-w-2xl mx-auto" style="color:var(--text-secondary)">
            Panduan lengkap mengenai perhitungan matematis, logika penentuan status, serta algoritma pencatatan otomatis yang tertanam di dalam sistem SISmart.
        </p>
    </div>

    {{-- Grid Konten --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- 1. Penilaian Stok & Harga Pokok Penjualan (HPP) --}}
        <div class="glass rounded-2xl p-6 space-y-5 relative overflow-hidden group">
            <div class="flex items-center gap-3 border-b pb-4" style="border-color:var(--border-color)">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-500 flex items-center justify-center text-lg"><i class="fas fa-calculator"></i></div>
                <h3 class="text-lg font-bold" style="color:var(--text-primary)">1. Penilaian HPP & Laba</h3>
            </div>
            
            <div class="space-y-4">
                <div class="p-4 rounded-xl border transition-colors hover:border-cyan-500/30" style="background:var(--bg-input);border-color:var(--border-color)">
                    <h4 class="font-bold text-sm mb-1 flex justify-between" style="color:var(--text-primary)"><span>Metode Rata-rata (Average)</span></h4>
                    <p class="text-[11px] mb-2 leading-relaxed" style="color:var(--text-secondary)">Nilai modal barang dihitung dari rata-rata nilai seluruh barang yang masuk ke gudang. HPP berubah dinamis setiap ada barang baru masuk.</p>
                    <div class="p-2.5 rounded-lg text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                        HPP = (Total Nilai Stok Lama + Nilai Masuk Baru) / Total Qty Stok
                    </div>
                </div>

                <div class="p-4 rounded-xl border transition-colors hover:border-cyan-500/30" style="background:var(--bg-input);border-color:var(--border-color)">
                    <h4 class="font-bold text-sm mb-1 flex justify-between" style="color:var(--text-primary)"><span>Metode FIFO (First-In, First-Out)</span></h4>
                    <p class="text-[11px] mb-2 leading-relaxed" style="color:var(--text-secondary)">Barang yang dikeluarkan akan mengambil harga modal dari urutan <i>batch</i> (gelombang) barang yang paling awal masuk (paling tua).</p>
                    <div class="p-2.5 rounded-lg text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                        HPP = Harga pada Batch Tertua yg Stoknya > 0
                    </div>
                </div>

                <div class="p-4 rounded-xl border transition-colors hover:border-emerald-500/30" style="background:var(--bg-input);border-color:var(--border-color)">
                    <h4 class="font-bold text-sm mb-1 flex justify-between" style="color:var(--text-primary)"><span>Laba / Rugi Transaksi</span></h4>
                    <p class="text-[11px] mb-2 leading-relaxed" style="color:var(--text-secondary)">Selisih antara harga yang ditawarkan ke pelanggan dengan Harga Pokok Penjualan (HPP).</p>
                    <div class="p-2.5 rounded-lg text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                        Laba Kotor = (Harga Jual - Harga Modal HPP) × Qty Terjual
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Manajemen Persediaan & Status --}}
        <div class="glass rounded-2xl p-6 space-y-5 relative overflow-hidden group">
            <div class="flex items-center gap-3 border-b pb-4" style="border-color:var(--border-color)">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-lg"><i class="fas fa-shield-alt"></i></div>
                <h3 class="text-lg font-bold" style="color:var(--text-primary)">2. Formula Persediaan & Status</h3>
            </div>
            
            <div class="space-y-4">
                <div class="p-4 rounded-xl border transition-colors hover:border-amber-500/30" style="background:var(--bg-input);border-color:var(--border-color)">
                    <h4 class="font-bold text-sm mb-1" style="color:var(--text-primary)">Safety Stock, Reorder Point, & Lead Time</h4>
                    <p class="text-[11px] mb-3 leading-relaxed" style="color:var(--text-secondary)">
                        Sistem menggunakan perhitungan persediaan modern untuk menentukan kapan Anda harus memesan barang dan berapa batas amannya. Terdapat 3 komponen penting dalam perhitungan ini:
                    </p>
                    <div class="mb-3 p-3 rounded-lg border text-[11px] leading-relaxed" style="background:var(--bg-sidebar);border-color:var(--border-color);color:var(--text-primary)">
                        <b>Lead Time (Waktu Tunggu):</b><br>
                        Adalah jeda waktu (dalam hari) sejak Anda memesan barang ke Supplier hingga barang tersebut tiba di gudang. Nilai Lead Time sangat memengaruhi perhitungan batas aman stok. Jika Lead Time panjang, Anda butuh stok pengaman lebih banyak.
                    </div>
                    <div class="space-y-2">
                        <div class="p-2 rounded text-xs font-mono font-bold flex gap-2 items-center" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                            <span class="text-amber-500">SS =</span> (Pemakaian Maksimum - Pemakaian Rata-rata) × Lead Time
                        </div>
                        <div class="p-2 rounded text-xs font-mono font-bold flex gap-2 items-center" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                            <span class="text-indigo-500">ROP =</span> (Pemakaian Rata-rata × Lead Time) + Safety Stock
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-xl border transition-colors hover:border-amber-500/30" style="background:var(--bg-input);border-color:var(--border-color)">
                    <h4 class="font-bold text-sm mb-2" style="color:var(--text-primary)">Indikator Status Stok (Otomatis)</h4>
                    <ul class="text-[11px] space-y-3" style="color:var(--text-secondary)">
                        <li class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-500 w-16 text-center">Habis</span>
                            <span>Jika <b>Total Stok = 0</b>.</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-500 w-16 text-center">Kritis</span>
                            <span>Jika <b>Total Stok ≤ Safety Stock</b> (Sangat Mendesak).</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/10 text-indigo-500 w-16 text-center">Reorder</span>
                            <span>Jika <b>Total Stok ≤ Reorder Point</b> (Harus segera dipesan).</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-500 w-16 text-center">Aman</span>
                            <span>Jika <b>Total Stok > Reorder Point</b>.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 3. Rasio Perputaran & Jurnal Keuangan --}}
        <div class="glass rounded-2xl p-6 space-y-5 relative overflow-hidden group">
            <div class="flex items-center gap-3 border-b pb-4" style="border-color:var(--border-color)">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-lg"><i class="fas fa-chart-bar"></i></div>
                <h3 class="text-lg font-bold" style="color:var(--text-primary)">3. Rasio Kecepatan & Jurnal</h3>
            </div>
            
            <div class="space-y-4">
                <div class="p-4 rounded-xl border transition-colors hover:border-purple-500/30" style="background:var(--bg-input);border-color:var(--border-color)">
                    <h4 class="font-bold text-sm mb-1 flex justify-between" style="color:var(--text-primary)"><span>Inventory Turnover Ratio (Perputaran Stok)</span></h4>
                    <p class="text-[11px] mb-2 leading-relaxed" style="color:var(--text-secondary)">Menunjukkan seberapa cepat suatu barang berputar (terjual/dipakai) dalam suatu periode (bulanan/tahunan). Angka yang tinggi menunjukkan barang laku keras.</p>
                    <div class="p-2.5 rounded-lg text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                        Rasio = Total Qty Keluar / Nilai Rata-rata Stok (Tahun/Bulan Ini)
                    </div>
                </div>

                <div class="p-4 rounded-xl border transition-colors hover:border-purple-500/30" style="background:var(--bg-input);border-color:var(--border-color)">
                    <h4 class="font-bold text-sm mb-2" style="color:var(--text-primary)">Jurnal Umum Otomatis</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-lg border text-[11px]" style="background:var(--bg-sidebar);border-color:var(--border-color)">
                            <div class="font-bold mb-1 text-emerald-500"><i class="fas fa-arrow-down mr-1"></i> Barang Masuk</div>
                            <div style="color:var(--text-secondary)"><b>(Db)</b> Persediaan Barang</div>
                            <div style="color:var(--text-secondary)"><b>(Cr)</b> Kas / Hutang</div>
                        </div>
                        <div class="p-3 rounded-lg border text-[11px]" style="background:var(--bg-sidebar);border-color:var(--border-color)">
                            <div class="font-bold mb-1 text-rose-500"><i class="fas fa-arrow-up mr-1"></i> Barang Keluar</div>
                            <div style="color:var(--text-secondary)"><b>(Db)</b> Kas / Piutang</div>
                            <div style="color:var(--text-secondary)"><b>(Cr)</b> Penjualan</div>
                            <div class="mt-1 border-t pt-1 border-gray-500/20"></div>
                            <div style="color:var(--text-secondary)"><b>(Db)</b> Beban Pokok (HPP)</div>
                            <div style="color:var(--text-secondary)"><b>(Cr)</b> Persediaan Barang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Kebijakan Sistem & Penghapusan --}}
        <div class="glass rounded-2xl p-6 space-y-5 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 opacity-5 text-rose-500 group-hover:scale-110 transition-transform duration-500"><i class="fas fa-exclamation-triangle text-9xl"></i></div>
            
            <div class="flex items-center gap-3 border-b pb-4 relative z-10" style="border-color:var(--border-color)">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center text-lg"><i class="fas fa-trash-alt"></i></div>
                <h3 class="text-lg font-bold text-rose-500">4. Aturan Penghapusan Data (Cascade)</h3>
            </div>
            
            <div class="p-5 rounded-xl bg-rose-500/10 border border-rose-500/20 relative z-10">
                <h4 class="font-bold text-sm mb-3 text-rose-600 dark:text-rose-400">🚨 Peringatan Penting (Cascade Delete)</h4>
                <p class="text-xs mb-4 leading-relaxed" style="color:var(--text-primary)">
                    Sistem basis data SISmart dirancang dengan relasi yang ketat untuk menjaga integritas pembukuan (tidak boleh ada data transaksi "menggantung" tanpa barang).
                </p>
                <ul class="text-[11px] space-y-3" style="color:var(--text-secondary)">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-caret-right text-rose-500 mt-0.5"></i> 
                        <span><b>Hapus Barang:</b> Jika sebuah "Barang" dihapus dari Master Data, maka <strong>SELURUH</strong> riwayat Barang Masuk, Barang Keluar, dan Stok Batch yang berkaitan dengan barang tersebut akan <strong>IKUT TERHAPUS SECARA PERMANEN</strong>. Pastikan data barang sudah tidak dibutuhkan atau memang salah diinput.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-caret-right text-amber-500 mt-0.5"></i> 
                        <span><b>Hapus Kategori:</b> Jika "Kategori" dihapus, data Barang <strong>TIDAK</strong> akan ikut terhapus. Kategori pada barang tersebut hanya akan berubah menjadi "Kosong" (Nullable).</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
