<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #4F46E5; padding-bottom: 15px; }
    .header h1 { font-size: 18px; color: #4F46E5; margin-bottom: 3px; } .header p { font-size: 10px; color: #64748b; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { padding: 8px 6px; font-size: 9px; text-align: left; color: white; background: #4F46E5; }
    td { padding: 6px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
    tr:nth-child(even) { background: #f8fafc; }
    .text-right { text-align: right; } .text-center { text-align: center; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head><body>
    <div class="header">
        <h1>SISmart - Smart Inventory System</h1>
        <p>LAPORAN PERPUTARAN STOK</p>
        <p>Per tanggal: {{ now()->translatedFormat('d F Y') }}</p>
    </div>
    <table>
        <thead><tr><th class="text-center" style="width:30px">No</th><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th class="text-right">Stok</th><th class="text-right">Total Masuk</th><th class="text-right">Total Keluar</th><th class="text-right">Rasio</th></tr></thead>
        <tbody>
            @forelse($barangs as $i => $b)
            <tr><td class="text-center">{{ $i+1 }}</td><td>{{ $b->kode }}</td><td>{{ $b->nama }}</td><td>{{ $b->kategori->nama ?? '-' }}</td><td class="text-right">{{ number_format($b->stok) }}</td><td class="text-right">{{ number_format($b->total_masuk) }}</td><td class="text-right">{{ number_format($b->total_keluar) }}</td><td class="text-right">{{ $b->rasio }}x</td></tr>
            @empty <tr><td colspan="8" class="text-center" style="padding:15px">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">Dicetak oleh {{ auth()->user()->name }} pada {{ now()->translatedFormat('d F Y H:i') }} — SISmart v1.0</div>
</body></html>
