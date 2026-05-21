<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #059669; padding-bottom: 15px; }
    .header h1 { font-size: 18px; color: #059669; margin-bottom: 3px; } .header p { font-size: 10px; color: #64748b; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { padding: 8px 6px; font-size: 9px; text-align: left; color: white; background: #059669; }
    td { padding: 6px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
    tr:nth-child(even) { background: #f8fafc; }
    .text-right { text-align: right; } .text-center { text-align: center; }
    .total-row { font-weight: bold; border-top: 2px solid #333; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head><body>
    <div class="header">
        <h1>SISmart - Smart Inventory System</h1>
        <p>{{ __('export.laporan_barang_masuk') }}</p>
        <p>{{ __('export.periode') }}: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} {{ __('export.sd') }} {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
    </div>
    <table>
        <thead><tr><th class="text-center" style="width:30px">{{ __('export.no') }}</th><th>{{ __('export.tanggal') }}</th><th>{{ __('export.kode') }}</th><th>{{ __('export.nama_barang') }}</th><th>{{ __('export.supplier') }}</th><th class="text-right">{{ __('export.jumlah') }}</th><th class="text-right">{{ __('export.harga_satuan') }}</th><th class="text-right">{{ __('export.total_rp') }}</th></tr></thead>
        <tbody>
            @forelse($data as $i => $m)
            <tr><td class="text-center">{{ $i+1 }}</td><td>{{ $m->tanggal->format('d/m/Y') }}</td><td>{{ $m->barang->kode ?? '-' }}</td><td>{{ $m->barang->nama ?? '-' }}</td><td>{{ $m->supplier ?? '-' }}</td><td class="text-right">{{ number_format($m->jumlah) }}</td><td class="text-right">Rp {{ number_format($m->harga_satuan,0,',','.') }}</td><td class="text-right">Rp {{ number_format($m->total_harga,0,',','.') }}</td></tr>
            @empty <tr><td colspan="8" class="text-center" style="padding:15px">{{ __('export.tidak_ada_data') }}</td></tr>
            @endforelse
            @if($data->count())<tr class="total-row"><td colspan="7" class="text-right">{{ __('export.total') }}</td><td class="text-right">Rp {{ number_format($total,0,',','.') }}</td></tr>@endif
        </tbody>
    </table>
    <div class="footer">{{ __('export.dicetak_oleh') }} {{ auth()->user()->name }} {{ __('export.pada') }} {{ now()->translatedFormat('d F Y H:i') }} — SISmart v1.0</div>
</body></html>
