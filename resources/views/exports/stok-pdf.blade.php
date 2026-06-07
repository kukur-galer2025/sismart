<!DOCTYPE html>
<html><head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #4F46E5; padding-bottom: 15px; }
        .header h1 { font-size: 18px; color: #4F46E5; margin-bottom: 3px; }
        .header p { font-size: 10px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #4F46E5; color: white; padding: 8px 6px; font-size: 9px; text-align: left; }
        td { padding: 6px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
        tr:nth-child(even) { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .total-row { background: #eef2ff !important; font-weight: bold; border-top: 2px solid #4F46E5; }
        .status-aman { color: #059669; } .status-kritis { color: #d97706; } .status-habis { color: #dc2626; } .status-reorder { color: #4F46E5; }
        .summary { display: flex; gap: 20px; margin-bottom: 15px; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 15px; border-radius: 6px; flex: 1; }
        .summary-box .label { font-size: 9px; color: #64748b; } .summary-box .value { font-size: 14px; font-weight: bold; color: #1e293b; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head><body>
    <div class="header">
        <h1>Kedana Kedini - Smart Inventory System</h1>
        <p>{{ __('export.laporan_persediaan') }}</p>
        <p>{{ __('export.tanggal') }}: {{ now()->translatedFormat('d F Y') }}</p>
    </div>
    <table>
        <tr style="margin-bottom:10px;">
            <td style="border:none;padding:8px;background:#f0fdf4;width:33%"><strong>{{ __('export.total_jenis') }}</strong> {{ $totalItem }} {{ __('export.barang') }}</td>
            <td style="border:none;padding:8px;background:#eff6ff;width:33%"><strong>{{ __('export.total_unit') }}</strong> {{ number_format($totalStok) }}</td>
            <td style="border:none;padding:8px;background:#fef3c7;width:33%"><strong>{{ __('export.total_nilai') }}:</strong> Rp {{ number_format($totalNilai,0,',','.') }}</td>
        </tr>
    </table>
    <table>
        <thead><tr>
            <th class="text-center" style="width:30px">{{ __('export.no') }}</th><th>{{ __('export.kode') }}</th><th>{{ __('export.nama_barang') }}</th><th>{{ __('export.kategori') }}</th><th class="text-center">{{ __('export.satuan') }}</th><th class="text-center">{{ __('export.metode') }}</th><th class="text-right">{{ __('export.stok') }}</th><th class="text-right">{{ __('export.harga_rata') }}</th><th class="text-right">{{ __('export.total_nilai') }}</th><th class="text-center">{{ __('export.status') }}</th>
        </tr></thead>
        <tbody>
            @foreach($barangs as $i => $b)
            <tr>
                <td class="text-center">{{ $i+1 }}</td><td>{{ $b->kode }}</td><td>{{ $b->nama }}</td><td>{{ $b->kategori->nama ?? '-' }}</td>
                <td class="text-center">{{ $b->satuan }}</td><td class="text-center font-bold">{{ strtoupper($b->metode_stok) }}</td>
                <td class="text-right">{{ number_format($b->stok) }}</td><td class="text-right">Rp {{ number_format($b->harga_rata_rata,0,',','.') }}</td>
                <td class="text-right">Rp {{ number_format($b->total_nilai,0,',','.') }}</td>
                <td class="text-center status-{{ strtolower($b->status_stok) }}"><strong>{{ $b->status_stok }}</strong></td>
            </tr>
            @endforeach
            <tr class="total-row"><td colspan="8" class="text-right">{{ __('export.grand_total') }}</td><td class="text-right">Rp {{ number_format($totalNilai,0,',','.') }}</td><td></td></tr>
        </tbody>
    </table>
    <div class="footer">{{ __('export.dicetak_oleh') }} {{ auth()->user()->name }} {{ __('export.pada') }} {{ now()->translatedFormat('d F Y H:i') }} — Kedana Kedini v1.0</div>
</body></html>
