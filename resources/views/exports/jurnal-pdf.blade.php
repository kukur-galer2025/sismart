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
    .text-right { text-align: right; } .text-center { text-align: center; } .font-bold { font-weight: bold; }
    .total-row { font-weight: bold; border-top: 2px solid #333; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head><body>
    <div class="header">
        <h1>SISmart - Smart Inventory System</h1>
        <p>{{ __('export.laporan_jurnal') }}</p>
        <p>{{ __('export.periode') }}: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} {{ __('export.sd') }} {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead><tr><th class="text-center" style="width:30px">{{ __('export.no') }}</th><th>{{ __('export.tanggal') }}</th><th>{{ __('export.akun') }}</th><th>{{ __('export.keterangan') }}</th><th class="text-right">{{ __('export.debit') }}</th><th class="text-right">{{ __('export.kredit') }}</th></tr></thead>
        <tbody>
            @forelse($jurnals as $i => $j)
            <tr><td class="text-center">{{ $i+1 }}</td><td>{{ $j->tanggal->format('d/m/Y') }}</td><td>{{ $j->akun->nama ?? '-' }}</td><td>{{ $j->keterangan ?? '-' }}</td><td class="text-right">{{ $j->debit > 0 ? 'Rp '.number_format($j->debit,0,',','.') : '-' }}</td><td class="text-right">{{ $j->kredit > 0 ? 'Rp '.number_format($j->kredit,0,',','.') : '-' }}</td></tr>
            @empty <tr><td colspan="6" class="text-center" style="padding:15px">{{ __('export.tidak_ada_data') }}</td></tr>
            @endforelse
            @if($jurnals->count())
            <tr class="total-row"><td colspan="4" class="text-right">{{ __('export.total') }}</td><td class="text-right">Rp {{ number_format($totalDebit,0,',','.') }}</td><td class="text-right">Rp {{ number_format($totalKredit,0,',','.') }}</td></tr>
            @endif
        </tbody>
    </table>
    <div class="footer">{{ __('export.dicetak_oleh') }} {{ auth()->user()->name }} {{ __('export.pada') }} {{ now()->translatedFormat('d F Y H:i') }} — SISmart v1.0</div>
</body></html>
