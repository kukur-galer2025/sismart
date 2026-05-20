<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #dc2626; padding-bottom: 15px; }
    .header h1 { font-size: 18px; color: #dc2626; margin-bottom: 3px; } .header p { font-size: 10px; color: #64748b; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { padding: 8px 6px; font-size: 9px; text-align: left; color: white; background: #dc2626; }
    td { padding: 6px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
    tr:nth-child(even) { background: #f8fafc; }
    .text-right { text-align: right; } .text-center { text-align: center; }
    .total-row { font-weight: bold; border-top: 2px solid #333; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head><body>
    <div class="header">
        <h1>SISmart - Smart Inventory System</h1>
        <p>LAPORAN BARANG KELUAR</p>
        <p>Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
    </div>
    <table>
        <thead><tr><th class="text-center" style="width:30px">No</th><th>Tanggal</th><th>Kode</th><th>Barang</th><th>Tujuan</th><th class="text-right">Jumlah</th><th class="text-right">Harga Satuan</th><th class="text-right">Total (Rp)</th></tr></thead>
        <tbody>
            @forelse($data as $i => $k)
            <tr><td class="text-center">{{ $i+1 }}</td><td>{{ $k->tanggal->format('d/m/Y') }}</td><td>{{ $k->barang->kode ?? '-' }}</td><td>{{ $k->barang->nama ?? '-' }}</td><td>{{ $k->tujuan ?? '-' }}</td><td class="text-right">{{ number_format($k->jumlah) }}</td><td class="text-right">Rp {{ number_format($k->harga_satuan,0,',','.') }}</td><td class="text-right">Rp {{ number_format($k->total_harga,0,',','.') }}</td></tr>
            @empty <tr><td colspan="8" class="text-center" style="padding:15px">Tidak ada data</td></tr>
            @endforelse
            @if($data->count())<tr class="total-row"><td colspan="7" class="text-right">Total</td><td class="text-right">Rp {{ number_format($total,0,',','.') }}</td></tr>@endif
        </tbody>
    </table>
    <div class="footer">Dicetak oleh {{ auth()->user()->name }} pada {{ now()->translatedFormat('d F Y H:i') }} — SISmart v1.0</div>
</body></html>
