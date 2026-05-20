<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #4F46E5; padding-bottom: 15px; }
    .header h1 { font-size: 18px; color: #4F46E5; margin-bottom: 3px; } .header p { font-size: 10px; color: #64748b; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { padding: 8px 6px; font-size: 9px; text-align: left; color: white; }
    td { padding: 6px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
    tr:nth-child(even) { background: #f8fafc; }
    .text-right { text-align: right; } .text-center { text-align: center; } .font-bold { font-weight: bold; }
    .total-row { font-weight: bold; border-top: 2px solid #333; }
    .section-title { font-size: 13px; font-weight: bold; margin: 20px 0 10px 0; padding: 8px; border-radius: 4px; }
    .bg-green { background: #059669; } .bg-red { background: #dc2626; }
    .summary-table td { border: none; padding: 8px; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head><body>
    <div class="header">
        <h1>SISmart - Smart Inventory System</h1>
        <p>LAPORAN TRANSAKSI</p>
        <p>Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td style="background:#f0fdf4;width:33%"><strong>Total Masuk:</strong> Rp {{ number_format($totalMasuk,0,',','.') }}</td>
            <td style="background:#fef2f2;width:33%"><strong>Total Keluar:</strong> Rp {{ number_format($totalKeluar,0,',','.') }}</td>
            <td style="background:#eff6ff;width:33%"><strong>Selisih:</strong> Rp {{ number_format($totalMasuk-$totalKeluar,0,',','.') }}</td>
        </tr>
    </table>

    <div class="section-title" style="color:#059669;background:#f0fdf4;">BARANG MASUK ({{ $masuk->count() }} transaksi)</div>
    <table>
        <thead><tr class="bg-green"><th class="text-center" style="width:30px">No</th><th>Tanggal</th><th>Kode TRX</th><th>Barang</th><th>Supplier</th><th class="text-right">Jumlah</th><th class="text-right">Total (Rp)</th></tr></thead>
        <tbody>
            @forelse($masuk as $i => $m)
            <tr><td class="text-center">{{ $i+1 }}</td><td>{{ $m->tanggal->format('d/m/Y') }}</td><td>{{ $m->kode_transaksi }}</td><td>{{ $m->barang->nama }}</td><td>{{ $m->supplier ?? '-' }}</td><td class="text-right">{{ number_format($m->jumlah) }}</td><td class="text-right">Rp {{ number_format($m->total_harga,0,',','.') }}</td></tr>
            @empty <tr><td colspan="7" class="text-center" style="padding:15px">Tidak ada data</td></tr>
            @endforelse
            @if($masuk->count()) <tr class="total-row"><td colspan="6" class="text-right">Total</td><td class="text-right">Rp {{ number_format($totalMasuk,0,',','.') }}</td></tr> @endif
        </tbody>
    </table>

    <div class="section-title" style="color:#dc2626;background:#fef2f2;">BARANG KELUAR ({{ $keluar->count() }} transaksi)</div>
    <table>
        <thead><tr class="bg-red"><th class="text-center" style="width:30px">No</th><th>Tanggal</th><th>Kode TRX</th><th>Barang</th><th>Tujuan</th><th class="text-right">Jumlah</th><th class="text-right">Total HPP (Rp)</th></tr></thead>
        <tbody>
            @forelse($keluar as $i => $k)
            <tr><td class="text-center">{{ $i+1 }}</td><td>{{ $k->tanggal->format('d/m/Y') }}</td><td>{{ $k->kode_transaksi }}</td><td>{{ $k->barang->nama }}</td><td>{{ $k->tujuan ?? '-' }}</td><td class="text-right">{{ number_format($k->jumlah) }}</td><td class="text-right">Rp {{ number_format($k->total_harga,0,',','.') }}</td></tr>
            @empty <tr><td colspan="7" class="text-center" style="padding:15px">Tidak ada data</td></tr>
            @endforelse
            @if($keluar->count()) <tr class="total-row"><td colspan="6" class="text-right">Total</td><td class="text-right">Rp {{ number_format($totalKeluar,0,',','.') }}</td></tr> @endif
        </tbody>
    </table>
    <div class="footer">Dicetak oleh {{ auth()->user()->name }} pada {{ now()->translatedFormat('d F Y H:i') }} — SISmart v1.0</div>
</body></html>
