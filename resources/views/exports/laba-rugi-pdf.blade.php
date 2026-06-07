<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }
    .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #4F46E5; padding-bottom: 15px; }
    .header h1 { font-size: 18px; color: #4F46E5; } .header p { font-size: 10px; color: #64748b; }
    .section { margin: 15px 0; } .section-title { font-size: 13px; font-weight: bold; padding: 8px 12px; border-radius: 4px; margin-bottom: 8px; }
    .row { display: flex; justify-content: space-between; padding: 8px 12px; border-bottom: 1px solid #e2e8f0; }
    .row:hover { background: #f8fafc; }
    .total-box { padding: 12px 15px; border-radius: 6px; margin-top: 8px; display: flex; justify-content: space-between; align-items: center; font-weight: bold; }
    .result-box { padding: 20px; border-radius: 8px; margin-top: 25px; text-align: center; }
    .text-right { text-align: right; } .font-bold { font-weight: bold; }
    .footer { margin-top: 30px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head><body>
    <div class="header">
        <h1>Kedana Kedini - Smart Inventory System</h1>
        <p>{{ __('export.laporan_laba_rugi') }}</p>
        <p>{{ __('export.periode') }}: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} {{ __('export.sd') }} {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title" style="color:#059669;background:#f0fdf4;">{{ __('export.pendapatan') }}</div>
        @foreach($pendapatan as $p)
        <div class="row"><span>{{ $p->nama }} ({{ $p->kode }})</span><span>Rp {{ number_format($p->saldo_periode,0,',','.') }}</span></div>
        @endforeach
        <div class="total-box" style="background:#f0fdf4;color:#059669;border:1px solid #a7f3d0;"><span>{{ __('export.total_pendapatan') }}</span><span style="font-size:14px">Rp {{ number_format($totalPendapatan,0,',','.') }}</span></div>
    </div>

    <div class="section">
        <div class="section-title" style="color:#dc2626;background:#fef2f2;">{{ __('export.beban') }}</div>
        @foreach($beban as $b)
        <div class="row"><span>{{ $b->nama }} ({{ $b->kode }})</span><span>Rp {{ number_format($b->saldo_periode,0,',','.') }}</span></div>
        @endforeach
        <div class="total-box" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;"><span>{{ __('export.total_beban') }}</span><span style="font-size:14px">Rp {{ number_format($totalBeban,0,',','.') }}</span></div>
    </div>

    <div class="result-box" style="background:{{ $labaRugi >= 0 ? '#f0fdf4' : '#fef2f2' }};border:2px solid {{ $labaRugi >= 0 ? '#059669' : '#dc2626' }};color:{{ $labaRugi >= 0 ? '#059669' : '#dc2626' }}">
        <p style="font-size:12px;margin-bottom:5px">{{ $labaRugi >= 0 ? __('export.laba_bersih') : __('export.rugi_bersih') }}</p>
        <p style="font-size:24px;font-weight:bold">Rp {{ number_format(abs($labaRugi),0,',','.') }}</p>
    </div>
    <div class="footer">{{ __('export.dicetak_oleh') }} {{ auth()->user()->name }} {{ __('export.pada') }} {{ now()->translatedFormat('d F Y H:i') }} — Kedana Kedini v1.0</div>
</body></html>
