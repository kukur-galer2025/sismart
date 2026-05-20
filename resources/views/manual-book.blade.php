@extends('layouts.app')
@section('title', 'Manual Book & Rumus Sistem')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="glass rounded-2xl p-6 sm:p-8">
        <h2 class="text-2xl font-bold mb-2 flex items-center gap-3" style="color:var(--text-primary)">
            <i class="fas fa-book-open text-indigo-500"></i> Manual Book & Penjelasan Rumus
        </h2>
        <p class="text-sm mb-8" style="color:var(--text-muted)">Halaman ini berisi dokumentasi rumus-rumus otomatis yang berjalan di belakang layar SISmart untuk memudahkan Admin memahami angka yang muncul di laporan.</p>

        <div class="space-y-8">
            
            {{-- 1. Penilaian Stok --}}
            <section>
                <h3 class="text-lg font-bold mb-3 flex items-center gap-2" style="color:var(--text-primary)"><i class="fas fa-boxes text-emerald-500"></i> 1. Metode Penilaian Stok & HPP</h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl border" style="background:var(--bg-input);border-color:var(--border-color)">
                        <h4 class="font-bold text-sm mb-2" style="color:var(--text-primary)">Metode Rata-rata (Average)</h4>
                        <p class="text-xs mb-3" style="color:var(--text-secondary)">Nilai barang dihitung berdasarkan harga rata-rata semua barang yang masuk.</p>
                        <div class="p-3 rounded-lg text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                            HPP = (Total Nilai Stok Lama + Total Nilai Masuk Baru) / Total Qty Stok
                        </div>
                    </div>
                    <div class="p-4 rounded-xl border" style="background:var(--bg-input);border-color:var(--border-color)">
                        <h4 class="font-bold text-sm mb-2" style="color:var(--text-primary)">Metode FIFO (First In First Out)</h4>
                        <p class="text-xs mb-3" style="color:var(--text-secondary)">Barang yang keluar diasumsikan mengambil harga modal dari barang yang paling pertama masuk.</p>
                        <div class="p-3 rounded-lg text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                            Harga Keluar = Harga Batch Masuk Terlama yang belum habis
                        </div>
                    </div>
                </div>
            </section>

            {{-- 2. Manajemen Persediaan --}}
            <section>
                <h3 class="text-lg font-bold mb-3 flex items-center gap-2" style="color:var(--text-primary)"><i class="fas fa-shield-alt text-amber-500"></i> 2. Rumus Safety Stock & Reorder Point</h3>
                <div class="p-5 rounded-xl border space-y-5" style="background:var(--bg-input);border-color:var(--border-color)">
                    <div>
                        <h4 class="font-bold text-sm mb-1" style="color:var(--text-primary)">Safety Stock (Stok Aman)</h4>
                        <p class="text-xs mb-2" style="color:var(--text-secondary)">Batas jumlah barang paling minimum untuk menghindari kehabisan stok akibat keterlambatan pengiriman.</p>
                        <div class="inline-block p-2 rounded-md text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                            Safety Stock = (Pemakaian Maksimal × Lead Time) - (Pemakaian Rata-rata × Lead Time)
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm mb-1" style="color:var(--text-primary)">Reorder Point (Titik Pemesanan Kembali)</h4>
                        <p class="text-xs mb-2" style="color:var(--text-secondary)">Titik di mana admin harus mulai memesan barang lagi ke supplier.</p>
                        <div class="inline-block p-2 rounded-md text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                            Reorder Point = (Pemakaian Rata-rata × Lead Time) + Safety Stock
                        </div>
                        <p class="text-xs mt-2 italic text-rose-500">*Sistem akan melabeli stok sebagai "Kritis" jika [Sisa Stok <= Reorder Point]</p>
                    </div>
                </div>
            </section>

            {{-- 3. Perputaran Stok --}}
            <section>
                <h3 class="text-lg font-bold mb-3 flex items-center gap-2" style="color:var(--text-primary)"><i class="fas fa-sync-alt text-cyan-500"></i> 3. Perputaran Stok (Inventory Turnover)</h3>
                <div class="p-4 rounded-xl border" style="background:var(--bg-input);border-color:var(--border-color)">
                    <p class="text-xs mb-3" style="color:var(--text-secondary)">Mengukur seberapa cepat barang terjual atau terpakai dalam periode tertentu. Semakin tinggi nilainya, semakin cepat barang tersebut berputar (laku).</p>
                    <div class="p-3 rounded-lg text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                        Inventory Turnover Ratio = Total Qty Keluar / Rata-rata Stok<br>
                        * Rata-rata Stok = (Stok Awal Periode + Stok Akhir Periode) / 2
                    </div>
                </div>
            </section>

            {{-- 4. Keuangan --}}
            <section>
                <h3 class="text-lg font-bold mb-3 flex items-center gap-2" style="color:var(--text-primary)"><i class="fas fa-chart-line text-rose-500"></i> 4. Laba Rugi & Jurnal Umum</h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl border" style="background:var(--bg-input);border-color:var(--border-color)">
                        <h4 class="font-bold text-sm mb-2" style="color:var(--text-primary)">Laba / Rugi Transaksi</h4>
                        <p class="text-xs mb-3" style="color:var(--text-secondary)">Keuntungan kotor yang dihitung dari selisih harga jual dan harga modal (HPP) setiap kali barang keluar.</p>
                        <div class="p-3 rounded-lg text-xs font-mono font-bold" style="background:var(--bg-sidebar);color:var(--text-primary);border:1px solid var(--border-color)">
                            Laba Kotor = (Harga Jual - Harga Modal) × Qty Keluar
                        </div>
                    </div>
                    <div class="p-4 rounded-xl border" style="background:var(--bg-input);border-color:var(--border-color)">
                        <h4 class="font-bold text-sm mb-2" style="color:var(--text-primary)">Pencatatan Jurnal Otomatis</h4>
                        <ul class="text-xs space-y-2" style="color:var(--text-secondary)">
                            <li><strong style="color:var(--text-primary)">Saat Barang Masuk:</strong><br> Debit: Persediaan Barang | Kredit: Kas/Hutang</li>
                            <li><strong style="color:var(--text-primary)">Saat Barang Keluar:</strong><br> Debit: Kas/Piutang | Kredit: Pendapatan Penjualan<br>
                            Debit: HPP | Kredit: Persediaan Barang</li>
                        </ul>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
