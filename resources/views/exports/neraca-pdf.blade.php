<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }
    .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #4F46E5; padding-bottom: 15px; }
    .header h1 { font-size: 18px; color: #4F46E5; } .header p { font-size: 10px; color: #64748b; }
    table { width: 100%; border-collapse: collapse; }
    .col { width: 50%; vertical-align: top; padding: 15px; }
    .section-title { font-size: 12px; font-weight: bold; padding: 8px; border-radius: 4px; margin-bottom: 10px; text-align: center; }
    .row { display: flex; justify-content: space-between; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
    .total-box { padding: 10px; border-radius: 6px; margin-top: 8px; display: flex; justify-content: space-between; font-weight: bold; font-size: 11px; }
    .balance-box { text-align: center; padding: 12px; margin-top: 15px; border-radius: 6px; font-weight: bold; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head><body>
    <div class="header">
        <h1>Kedana Kedini - Smart Inventory System</h1>
        <p>{{ __('export.laporan_neraca') }}</p>
        <p>{{ __('export.per_tanggal') }} {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
    </div>

    <table><tr>
        <td class="col" style="border-right:2px solid #e2e8f0;">
            <div class="section-title" style="color:#0891b2;background:#ecfeff;">{{ __('export.aset') }}</div>
            @foreach($aset as $a)
            <div class="row"><span>{{ $a->nama }}</span><span>Rp {{ number_format($a->saldo_periode,0,',','.') }}</span></div>
            @endforeach
            <div class="total-box" style="background:#ecfeff;color:#0891b2;border:1px solid #a5f3fc;"><span>{{ __('export.total_aset') }}</span><span>Rp {{ number_format($totalAset,0,',','.') }}</span></div>
        </td>
        <td class="col">
            <div class="section-title" style="color:#d97706;background:#fffbeb;">{{ __('export.kewajiban') }}</div>
            @forelse($kewajiban as $k)
            <div class="row"><span>{{ $k->nama }}</span><span>Rp {{ number_format($k->saldo_periode,0,',','.') }}</span></div>
            @empty <div class="row"><span style="color:#94a3b8">{{ __('export.tidak_ada_kewajiban') }}</span><span>Rp 0</span></div>
            @endforelse
            <div class="total-box" style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;"><span>{{ __('export.total_kewajiban') }}</span><span>Rp {{ number_format($totalKewajiban,0,',','.') }}</span></div>

            <div class="section-title" style="color:#7c3aed;background:#f5f3ff;margin-top:15px;">{{ __('export.ekuitas') }}</div>
            @foreach($ekuitas as $e)
            <div class="row"><span>{{ $e->nama }}</span><span>Rp {{ number_format($e->saldo_periode,0,',','.') }}</span></div>
            @endforeach
            <div class="total-box" style="background:#f5f3ff;color:#7c3aed;border:1px solid #ddd6fe;"><span>{{ __('export.total_ekuitas') }}</span><span>Rp {{ number_format($totalEkuitas,0,',','.') }}</span></div>

            <div class="total-box" style="background:#ecfeff;color:#0891b2;border:1px solid #a5f3fc;margin-top:10px;"><span>{{ __('export.kewajiban_ekuitas') }}</span><span>Rp {{ number_format($totalKewajiban+$totalEkuitas,0,',','.') }}</span></div>
        </td>
    </tr></table>

    <div class="balance-box" style="background:{{ abs($totalAset-($totalKewajiban+$totalEkuitas)) < 1 ? '#f0fdf4;color:#059669;border:2px solid #a7f3d0' : '#fef2f2;color:#dc2626;border:2px solid #fecaca' }}">
        {{ abs($totalAset-($totalKewajiban+$totalEkuitas)) < 1 ? __('export.seimbang') : __('export.tidak_seimbang').number_format(abs($totalAset-($totalKewajiban+$totalEkuitas)),0,',','.') }}
    </div>
    <div class="footer">{{ __('export.dicetak_oleh') }} {{ auth()->user()->name }} {{ __('export.pada') }} {{ now()->translatedFormat('d F Y H:i') }} — Kedana Kedini v1.0</div>
</body></html>
