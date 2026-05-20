@extends('layouts.app')
@section('title', 'Detail: ' . $barang->kode)
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <a href="{{ route('barang.index') }}" class="flex items-center gap-2 transition-colors text-sm" style="color:var(--text-muted)"><i class="fas fa-arrow-left"></i> Kembali</a>
        <a href="{{ route('barang.edit', $barang->id) }}" class="btn-primary text-xs"><i class="fas fa-edit"></i> Edit Barang</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="glass rounded-2xl p-5 sm:p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">{{ $barang->nama }}</h2>
                        <p class="text-indigo-600 dark:text-indigo-400 font-mono text-sm">{{ $barang->kode }}</p>
                    </div>
                    @php
                        $status = $barang->status_stok;
                        $color = match($status) {
                            'Aman' => 'text-emerald-700 dark:text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                            'Kritis' => 'text-amber-700 dark:text-amber-400 bg-amber-500/10 border-amber-500/20',
                            'Reorder' => 'text-indigo-700 dark:text-indigo-400 bg-indigo-500/10 border-indigo-500/20',
                            'Habis' => 'text-rose-700 dark:text-rose-400 bg-rose-500/10 border-rose-500/20',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $color }}">{{ $status }}</span>
                </div>

                <div class="space-y-3 border-t pt-4" style="border-color:var(--border-color)">
                    <div>
                        <p class="text-[11px] uppercase tracking-wider mb-0.5" style="color:var(--text-muted)">Kategori</p>
                        <p class="text-sm" style="color:var(--text-secondary)"><i class="fas fa-tag mr-2 text-indigo-400"></i>{{ $barang->kategori->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider mb-0.5" style="color:var(--text-muted)">Lokasi</p>
                        <p class="text-sm" style="color:var(--text-secondary)"><i class="fas fa-map-marker-alt mr-2 text-indigo-400"></i>{{ $barang->lokasi ?? 'Belum ditentukan' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider mb-0.5" style="color:var(--text-muted)">Metode Stok</p>
                        <p class="text-sm uppercase tracking-wider font-bold {{ $barang->metode_stok == 'fifo' ? 'text-cyan-600 dark:text-cyan-400' : 'text-fuchsia-600 dark:text-fuchsia-400' }}">{{ $barang->metode_stok }}</p>
                    </div>
                    @if($barang->keterangan)
                    <div>
                        <p class="text-[11px] uppercase tracking-wider mb-0.5" style="color:var(--text-muted)">Keterangan</p>
                        <p class="text-sm p-3 rounded-xl border" style="color:var(--text-secondary); background:var(--bg-input); border-color:var(--border-color)">{{ $barang->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="glass rounded-2xl p-5 sm:p-6 border-t-4 border-indigo-500">
                <h3 class="font-semibold mb-4 flex items-center gap-2"><i class="fas fa-calculator text-indigo-500"></i> Smart Calculation</h3>
                <div class="space-y-3">
                    @foreach([
                        ['Safety Stock (Set)', $barang->safety_stock . ' ' . $barang->satuan, ''],
                        ['Safety Stock (Ideal)', $safetyStockCalc . ' ' . $barang->satuan, ''],
                        ['Lead Time', $barang->lead_time . ' Hari', ''],
                    ] as [$label, $val, $class])
                    <div class="flex justify-between items-center pb-2 border-b" style="border-color:var(--border-color)">
                        <span class="text-sm" style="color:var(--text-muted)">{{ $label }}</span>
                        <span class="text-sm font-medium font-mono">{{ $val }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between items-center py-3 px-4 bg-indigo-500/10 -mx-5 sm:-mx-6 rounded-lg">
                        <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">Reorder Point (ROP)</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $rop }} {{ $barang->satuan }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-sm" style="color:var(--text-muted)">Turnover (Bulan Ini)</span>
                        <span class="text-sm font-medium {{ $perputaran > 1 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">{{ $perputaran }}x</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="glass p-5 rounded-2xl stat-card">
                    <p class="text-sm font-medium mb-1" style="color:var(--text-muted)">Stok Saat Ini</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl sm:text-4xl font-bold">{{ number_format($barang->stok) }}</h3>
                        <span class="text-sm" style="color:var(--text-muted)">{{ $barang->satuan }}</span>
                    </div>
                </div>
                <div class="glass p-5 rounded-2xl stat-card">
                    <p class="text-sm font-medium mb-1" style="color:var(--text-muted)">Total Nilai Persediaan</p>
                    <div class="flex items-baseline gap-2 flex-wrap">
                        <span class="text-lg text-cyan-600 dark:text-cyan-400">Rp</span>
                        <h3 class="text-2xl sm:text-3xl font-bold">{{ number_format($barang->total_nilai, 0, ',', '.') }}</h3>
                    </div>
                    <p class="text-xs mt-2" style="color:var(--text-muted)">Harga Rata²: Rp {{ number_format($barang->harga_rata_rata, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($barang->metode_stok == 'fifo')
            <div class="glass rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b bg-indigo-500/5 dark:bg-white/5" style="border-color:var(--border-color)">
                    <h3 class="font-semibold text-sm">Rincian Batch (FIFO)</h3>
                </div>
                <div class="table-responsive">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase" style="color:var(--text-muted); background:var(--bg-input)">
                            <tr><th class="px-5 py-3">Tanggal Masuk</th><th class="px-5 py-3">Harga Satuan</th><th class="px-5 py-3 text-right">Sisa Stok</th><th class="px-5 py-3 text-right">Nilai</th></tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:var(--border-color)">
                            @forelse($barang->batches as $batch)
                            <tr class="hover:bg-indigo-500/5 dark:hover:bg-white/5">
                                <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $batch->tanggal_masuk->format('d M Y') }}</td>
                                <td class="px-5 py-3" style="color:var(--text-secondary)">Rp {{ number_format($batch->harga_satuan, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right font-medium">{{ number_format($batch->jumlah_sisa) }}</td>
                                <td class="px-5 py-3 text-right text-cyan-600 dark:text-cyan-400">Rp {{ number_format($batch->jumlah_sisa * $batch->harga_satuan, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-5 py-6 text-center text-xs" style="color:var(--text-muted)">Tidak ada batch / stok habis</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div x-data="{ tab: 'masuk' }" class="glass rounded-2xl overflow-hidden">
                <div class="flex border-b" style="border-color:var(--border-color)">
                    <button @click="tab='masuk'" :class="tab==='masuk' ? 'text-emerald-600 dark:text-emerald-400 border-b-2 border-emerald-500' : ''" class="flex-1 py-3.5 text-sm font-medium transition-colors" :style="tab!=='masuk' ? 'color:var(--text-muted)' : ''">
                        <i class="fas fa-arrow-down mr-1"></i> Riwayat Masuk
                    </button>
                    <button @click="tab='keluar'" :class="tab==='keluar' ? 'text-rose-600 dark:text-rose-400 border-b-2 border-rose-500' : ''" class="flex-1 py-3.5 text-sm font-medium transition-colors" :style="tab!=='keluar' ? 'color:var(--text-muted)' : ''">
                        <i class="fas fa-arrow-up mr-1"></i> Riwayat Keluar
                    </button>
                </div>
                <div x-show="tab==='masuk'" class="table-responsive">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase" style="color:var(--text-muted); background:var(--bg-input)"><tr><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Kode TRX</th><th class="px-5 py-3 hidden sm:table-cell">Supplier</th><th class="px-5 py-3 text-right">Jumlah</th></tr></thead>
                        <tbody class="divide-y" style="border-color:var(--border-color)">
                            @forelse($barang->barangMasuks as $trx)
                            <tr class="hover:bg-indigo-500/5 dark:hover:bg-white/5">
                                <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $trx->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 font-mono text-xs"><a href="{{ route('barang-masuk.show', $trx->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $trx->kode_transaksi }}</a></td>
                                <td class="px-5 py-3 hidden sm:table-cell" style="color:var(--text-muted)">{{ $trx->supplier ?? '-' }}</td>
                                <td class="px-5 py-3 text-right text-emerald-600 dark:text-emerald-400 font-medium">+{{ number_format($trx->jumlah) }}</td>
                            </tr>
                            @empty <tr><td colspan="4" class="px-5 py-6 text-center text-xs" style="color:var(--text-muted)">Belum ada riwayat masuk</td></tr> @endforelse
                        </tbody>
                    </table>
                </div>
                <div x-show="tab==='keluar'" x-cloak class="table-responsive">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase" style="color:var(--text-muted); background:var(--bg-input)"><tr><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Kode TRX</th><th class="px-5 py-3 hidden sm:table-cell">Tujuan</th><th class="px-5 py-3 text-right">Jumlah</th></tr></thead>
                        <tbody class="divide-y" style="border-color:var(--border-color)">
                            @forelse($barang->barangKeluars as $trx)
                            <tr class="hover:bg-indigo-500/5 dark:hover:bg-white/5">
                                <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $trx->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 font-mono text-xs"><a href="{{ route('barang-keluar.show', $trx->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $trx->kode_transaksi }}</a></td>
                                <td class="px-5 py-3 hidden sm:table-cell" style="color:var(--text-muted)">{{ $trx->tujuan ?? '-' }}</td>
                                <td class="px-5 py-3 text-right text-rose-600 dark:text-rose-400 font-medium">-{{ number_format($trx->jumlah) }}</td>
                            </tr>
                            @empty <tr><td colspan="4" class="px-5 py-6 text-center text-xs" style="color:var(--text-muted)">Belum ada riwayat keluar</td></tr> @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
