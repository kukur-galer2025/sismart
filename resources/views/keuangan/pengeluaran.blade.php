@extends('layouts.app')
@section('title', 'Input Pengeluaran & Prive')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="glass rounded-2xl p-6">
        <h2 class="text-xl font-bold mb-1">Input Pengeluaran / Prive</h2>
        <p class="text-sm mb-6" style="color:var(--text-muted)">Catat pengeluaran operasional (seperti listrik, air, gaji) atau penarikan modal (prive).</p>
        
        <form action="{{ route('keuangan.pengeluaran.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="form-label"><i class="fas fa-calendar-alt"></i> Tanggal</label>
                    <input type="date" name="tanggal" class="form-input" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                </div>
                <div>
                    <label class="form-label"><i class="fas fa-wallet"></i> Akun Tujuan</label>
                    <select name="akun_id" class="form-input" required>
                        <option value="">Pilih Akun...</option>
                        @foreach($akuns as $akun)
                            <option value="{{ $akun->id }}" {{ old('akun_id') == $akun->id ? 'selected' : '' }}>
                                {{ $akun->kode }} - {{ $akun->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mb-5">
                <label class="form-label"><i class="fas fa-money-bill-wave"></i> Nominal (Rp)</label>
                <input type="number" name="nominal" class="form-input" min="1" value="{{ old('nominal') }}" required placeholder="Contoh: 150000">
            </div>

            <div class="mb-6">
                <label class="form-label"><i class="fas fa-align-left"></i> Keterangan</label>
                <input type="text" name="keterangan" class="form-input" value="{{ old('keterangan') }}" required placeholder="Contoh: Pembayaran listrik bulan ini">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan Catatan</button>
            </div>
        </form>
    </div>

    <!-- History -->
    @if($history->count() > 0)
    <div class="glass rounded-2xl p-6">
        <h3 class="text-lg font-bold mb-4">Riwayat Terakhir</h3>
        <div class="table-responsive">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr style="background:var(--bg-input)">
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase" style="color:var(--text-muted)">Tanggal</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase" style="color:var(--text-muted)">Akun</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase" style="color:var(--text-muted)">Keterangan</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase text-right" style="color:var(--text-muted)">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $item)
                    <tr class="border-t transition-colors hover:bg-indigo-500/[.03]" style="border-color:var(--border-color)">
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $item->akun->nama }}</td>
                        <td class="px-4 py-3" style="color:var(--text-secondary)">{{ $item->keterangan }}</td>
                        <td class="px-4 py-3 text-right text-rose-600 dark:text-rose-400 font-bold">Rp {{ number_format($item->debit, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
