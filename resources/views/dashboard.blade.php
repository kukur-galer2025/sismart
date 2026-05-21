@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="space-y-5">
    {{-- Greeting --}}
    @php $hour = now()->hour; $greetKey = $hour < 12 ? 'dash.greeting_pagi' : ($hour < 15 ? 'dash.greeting_siang' : ($hour < 18 ? 'dash.greeting_sore' : 'dash.greeting_malam')); $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam')); $icon = $hour < 12 ? 'sun' : ($hour < 18 ? 'cloud-sun' : 'moon'); @endphp
    <div class="glass rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold flex items-center gap-2">
                <i class="fas fa-{{ $icon }} text-amber-500"></i> <span data-lang="{{ $greetKey }}">{{ $greet }}</span>, {{ auth()->user()->name }}!
            </h2>
            <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">{{ now()->translatedFormat('l, d F Y') }} — <span data-lang="dash.summary">Ini ringkasan sistem Anda hari ini.</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('barang-masuk.create') }}" class="btn-success !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-arrow-down"></i> <span data-lang="dash.masuk">Masuk</span></a>
            <a href="{{ route('barang-keluar.create') }}" class="btn-danger !text-[11px] !py-1.5 !px-2.5"><i class="fas fa-arrow-up"></i> <span data-lang="dash.keluar">Keluar</span></a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        @php
            $stats = [
                ['dash.total_barang', 'Total Barang', number_format($totalBarang), 'fa-boxes', '99,102,241', 'indigo', 'dash.aktif', 'Aktif di sistem', 'arrow-up', 'emerald'],
                ['dash.total_stok', 'Total Stok', number_format($totalStok), 'fa-cubes', '6,182,212', 'cyan', 'dash.unit_tersedia', 'Total unit tersedia', '', ''],
                ['dash.nilai_persediaan', 'Nilai Persediaan', 'Rp '.number_format($nilaiPersediaan,0,',','.'), 'fa-coins', '16,185,129', 'emerald', 'dash.estimasi_aset', 'Estimasi nilai aset', '', ''],
                ['dash.stok_kritis', 'Stok Kritis', $stokKritis, 'fa-exclamation-triangle', '239,68,68', 'rose', $stokKritis > 0 ? 'dash.butuh_perhatian' : 'dash.semua_aman', $stokKritis > 0 ? 'Butuh perhatian' : 'Semua aman', $stokKritis > 0 ? 'exclamation-circle' : 'check-circle', $stokKritis > 0 ? 'rose' : 'emerald']
            ];
        @endphp
        @foreach($stats as [$langKey, $label, $value, $icon, $rgb, $color, $noteKey, $note, $noteIcon, $noteColor])
        <div class="glass p-4 sm:p-5 rounded-2xl stat-card">
            <div class="flex justify-between items-start gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] sm:text-xs font-medium truncate" style="color:var(--text-muted)" data-lang="{{ $langKey }}">{{ $label }}</p>
                    <h3 class="text-lg sm:text-2xl font-bold mt-1 truncate {{ $langKey === 'dash.stok_kritis' && $stokKritis > 0 ? 'text-rose-600 dark:text-rose-400' : '' }}">{{ $value }}</h3>
                </div>
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba({{ $rgb }},0.12)">
                    <i class="fas {{ $icon }} text-{{ $color }}-500 text-sm sm:text-base"></i>
                </div>
            </div>
            @if($noteIcon)
            <p class="text-[10px] sm:text-xs mt-2 text-{{ $noteColor }}-600 dark:text-{{ $noteColor }}-400"><i class="fas fa-{{ $noteIcon }} mr-0.5"></i><span data-lang="{{ $noteKey }}">{{ $note }}</span></p>
            @else
            <p class="text-[10px] sm:text-xs mt-2" style="color:var(--text-muted)" data-lang="{{ $noteKey }}">{{ $note }}</p>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Notifications --}}
    @if(count($notifications) > 0)
    <div x-data="{ open:true }" class="glass rounded-2xl overflow-hidden" style="border-color:rgba(239,68,68,0.2)">
        <button @click="open=!open" class="w-full px-4 py-3 flex items-center justify-between" style="background:rgba(239,68,68,0.06)">
            <span class="flex items-center gap-2 text-sm font-semibold text-rose-700 dark:text-rose-400"><i class="fas fa-bell pulse-dot"></i> <span data-lang="dash.peringatan">Peringatan</span> ({{ count($notifications) }})</span>
            <i class="fas fa-chevron-down transition-transform text-xs" :class="open&&'rotate-180'" style="color:var(--text-muted)"></i>
        </button>
        <div x-show="open" x-collapse class="p-2 max-h-40 overflow-y-auto">
            @foreach($notifications as $n)
            <div class="p-3 text-sm flex items-center gap-3 rounded-lg" style="color:var(--text-secondary)"><i class="fas fa-{{ $n['icon'] }} {{ $n['type']==='danger'?'text-rose-500':($n['type']==='warning'?'text-amber-500':'text-cyan-500') }} shrink-0"></i><span>{{ $n['message'] }}</span></div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Row 1: Trend + Kategori --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">
        <div class="glass p-4 sm:p-5 rounded-2xl lg:col-span-2">
            <h3 class="font-semibold mb-4 text-sm flex items-center gap-2"><i class="fas fa-chart-area text-indigo-500"></i> <span data-lang="dash.trend_transaksi">Trend Transaksi (Nilai)</span></h3>
            <div id="chartTrend" class="w-full" style="min-height:220px; height:260px"></div>
        </div>
        <div class="glass p-4 sm:p-5 rounded-2xl">
            <h3 class="font-semibold mb-4 text-sm flex items-center gap-2"><i class="fas fa-chart-pie text-cyan-500"></i> <span data-lang="dash.stok_kategori">Stok per Kategori</span></h3>
            <div id="chartKategori" class="w-full" style="min-height:220px; height:260px"></div>
        </div>
    </div>

    {{-- Row 2: Volume + Status + Stok Terbanyak --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        <div class="glass p-4 sm:p-5 rounded-2xl">
            <h3 class="font-semibold mb-4 text-sm flex items-center gap-2"><i class="fas fa-chart-bar text-emerald-500"></i> <span data-lang="dash.volume_transaksi">Volume Transaksi</span></h3>
            <div id="chartVolume" class="w-full" style="min-height:200px; height:240px"></div>
        </div>
        <div class="glass p-4 sm:p-5 rounded-2xl">
            <h3 class="font-semibold mb-4 text-sm flex items-center gap-2"><i class="fas fa-signal text-amber-500"></i> <span data-lang="dash.status_stok">Status Stok</span></h3>
            <div id="chartStatus" class="w-full" style="min-height:200px; height:240px"></div>
        </div>
        <div class="glass p-4 sm:p-5 rounded-2xl sm:col-span-2 lg:col-span-1">
            <h3 class="font-semibold mb-4 text-sm flex items-center gap-2"><i class="fas fa-ranking-star text-purple-500"></i> <span data-lang="dash.stok_terbanyak">Stok Terbanyak</span></h3>
            <div id="chartTopItems" class="w-full" style="min-height:200px; height:240px"></div>
        </div>
    </div>

    {{-- Row 3: Nilai Kategori + Recent --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
        <div class="glass p-4 sm:p-5 rounded-2xl">
            <h3 class="font-semibold mb-4 text-sm flex items-center gap-2"><i class="fas fa-wallet text-rose-500"></i> <span data-lang="dash.nilai_kategori">Nilai per Kategori</span></h3>
            <div id="chartNilaiKat" class="w-full" style="min-height:200px; height:240px"></div>
        </div>
        <div class="glass rounded-2xl overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center" style="border-color:var(--border-color)">
                <h3 class="font-semibold text-sm flex items-center gap-2"><i class="fas fa-clock text-indigo-500"></i> <span data-lang="dash.transaksi_terbaru">Transaksi Terbaru</span></h3>
            </div>
            <div class="table-responsive" style="max-height:250px; overflow-y:auto">
                <table class="w-full text-sm text-left">
                    <tbody>
                        @foreach($recentMasuk->take(3) as $trx)
                        <tr class="border-t hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]" style="border-color:var(--border-color)">
                            <td class="px-4 py-2.5 text-xs" style="color:var(--text-muted)">{{ $trx->tanggal->format('d/m') }}</td>
                            <td class="px-4 py-2.5 font-medium text-xs truncate max-w-[120px]">{{ $trx->barang->nama }}</td>
                            <td class="px-4 py-2.5 text-right text-emerald-600 dark:text-emerald-400 text-xs font-semibold whitespace-nowrap">+{{ number_format($trx->jumlah) }}</td>
                        </tr>
                        @endforeach
                        @foreach($recentKeluar->take(3) as $trx)
                        <tr class="border-t hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03]" style="border-color:var(--border-color)">
                            <td class="px-4 py-2.5 text-xs" style="color:var(--text-muted)">{{ $trx->tanggal->format('d/m') }}</td>
                            <td class="px-4 py-2.5 font-medium text-xs truncate max-w-[120px]">{{ $trx->barang->nama }}</td>
                            <td class="px-4 py-2.5 text-right text-rose-600 dark:text-rose-400 text-xs font-semibold whitespace-nowrap">-{{ number_format($trx->jumlah) }}</td>
                        </tr>
                        @endforeach
                        @if($recentMasuk->isEmpty() && $recentKeluar->isEmpty())
                        <tr><td colspan="3" class="px-4 py-8 text-center text-xs" style="color:var(--text-muted)" data-lang="dash.belum_ada_transaksi">Belum ada transaksi</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dk = document.documentElement.classList.contains('dark');
    const lbl = dk?'#94a3b8':'#64748b';
    const grd = dk?'rgba(255,255,255,0.04)':'rgba(0,0,0,0.04)';
    const leg = dk?'#cbd5e1':'#374151';
    const tt = dk?'dark':'light';
    const base = {
        chart:{toolbar:{show:false},background:'transparent',fontFamily:'Inter'},
        theme:{mode:tt},tooltip:{theme:tt},
        grid:{borderColor:grd,strokeDashArray:4}
    };

    // 1. Trend Transaksi (Area)
    new ApexCharts(document.querySelector("#chartTrend"), {...base,
        series:[{name:'Masuk',data:@json($chartData['masuk'])},{name:'Keluar',data:@json($chartData['keluar'])}],
        chart:{...base.chart,type:'area',height:260},colors:['#10b981','#ef4444'],
        fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.35,opacityTo:0.05}},
        dataLabels:{enabled:false},stroke:{curve:'smooth',width:2},
        xaxis:{categories:@json($chartData['labels']),labels:{style:{colors:lbl,fontSize:'10px'}},axisBorder:{show:false},axisTicks:{show:false}},
        yaxis:{labels:{style:{colors:lbl},formatter:v=>v>=1e9?(v/1e9).toFixed(1)+' Miliar':v>=1e6?(v/1e6).toFixed(1)+' Jt':v>=1e3?(v/1e3).toFixed(0)+' Rb':v}},
        legend:{position:'top',horizontalAlign:'right',labels:{colors:leg},fontSize:'11px'}
    }).render();

    // 2. Stok per Kategori (Donut)
    const cd=@json($stockByCategory),cl=cd.map(c=>c.nama),cs=cd.map(c=>parseInt(c.barangs_sum_stok||0));
    if(cs.some(v=>v>0)){
        new ApexCharts(document.querySelector("#chartKategori"),{...base,
            series:cs,labels:cl,chart:{...base.chart,type:'donut',height:260},
            colors:['#6366f1','#06b6d4','#10b981','#f59e0b','#8b5cf6','#ec4899'],
            stroke:{show:false},dataLabels:{enabled:false},
            legend:{position:'bottom',labels:{colors:leg},fontSize:'10px'},
            plotOptions:{pie:{donut:{size:'72%',labels:{show:true,name:{color:lbl,fontSize:'11px'},value:{color:dk?'#f1f5f9':'#0f172a',fontSize:'18px',fontWeight:700},total:{show:true,color:dk?'#f1f5f9':'#0f172a',fontSize:'18px',fontWeight:700}}}}}
        }).render();
    } else { document.querySelector("#chartKategori").innerHTML='<div class="h-full flex items-center justify-center text-sm" style="color:var(--text-muted)">Belum ada data</div>'; }

    // 3. Volume Transaksi (Bar)
    new ApexCharts(document.querySelector("#chartVolume"),{...base,
        series:[{name:'Masuk',data:@json($txCountData['masuk'])},{name:'Keluar',data:@json($txCountData['keluar'])}],
        chart:{...base.chart,type:'bar',height:240,stacked:false},colors:['#10b981','#ef4444'],
        plotOptions:{bar:{borderRadius:4,columnWidth:'50%'}},dataLabels:{enabled:false},
        xaxis:{categories:@json($txCountData['labels']),labels:{style:{colors:lbl,fontSize:'10px'}},axisBorder:{show:false},axisTicks:{show:false}},
        yaxis:{labels:{style:{colors:lbl}},forceNiceScale:true},legend:{position:'top',labels:{colors:leg},fontSize:'10px'}
    }).render();

    // 4. Status Stok (Polar Area)
    const ss=@json($stockStatus), ssv=Object.values(ss), ssl=Object.keys(ss);
    new ApexCharts(document.querySelector("#chartStatus"),{...base,
        series:ssv,labels:ssl,chart:{...base.chart,type:'polarArea',height:240},
        colors:['#10b981','#6366f1','#f59e0b','#ef4444'],stroke:{colors:[dk?'#1a1145':'#fff'],width:2},
        fill:{opacity:0.85},dataLabels:{enabled:false},
        legend:{position:'bottom',labels:{colors:leg},fontSize:'10px'},
        plotOptions:{polarArea:{rings:{strokeWidth:0}}}
    }).render();

    // 5. Stok Terbanyak (Bar chart with item names + stock qty)
    const ti=@json($topItems);
    if(ti.length>0){
        new ApexCharts(document.querySelector("#chartTopItems"),{...base,
            series:[{name:'Stok Tersedia',data:ti.map(i=>i.stok)}],
            chart:{...base.chart,type:'bar',height:240},colors:['#8b5cf6'],
            plotOptions:{bar:{horizontal:true,borderRadius:4,barHeight:'60%',distributed:true}},
            colors:['#6366f1','#06b6d4','#10b981','#f59e0b','#8b5cf6'],
            dataLabels:{enabled:true,style:{fontSize:'11px',colors:[dk?'#e2e8f0':'#1e293b']},formatter:function(v,o){return ti[o.dataPointIndex].nama.substring(0,12)+(ti[o.dataPointIndex].nama.length>12?'…':'')+' — '+v+' '+ti[o.dataPointIndex].satuan;}},
            xaxis:{categories:ti.map(i=>i.nama),labels:{style:{colors:lbl,fontSize:'10px'}},axisBorder:{show:false}},
            yaxis:{show:false},
            legend:{show:false},
            tooltip:{
                custom: function({dataPointIndex:i}) {
                    const item = ti[i];
                    return `<div class="px-3 py-2 shadow-lg text-xs" style="background:var(--bg-card); border:1px solid var(--border-color); border-radius:0.5rem; max-width:180px; white-space:normal; line-height:1.5;">
                        <div class="font-bold mb-1" style="color:var(--text-primary)">${item.nama}</div>
                        <div style="color:var(--text-secondary)">Stok: <span class="font-medium" style="color:var(--text-primary)">${item.stok.toLocaleString('id-ID')} ${item.satuan}</span></div>
                        <div style="color:var(--text-secondary)">Nilai: <span class="font-medium" style="color:var(--text-primary)">Rp ${parseFloat(item.total_nilai).toLocaleString('id-ID')}</span></div>
                    </div>`;
                }
            }
        }).render();
    } else { document.querySelector("#chartTopItems").innerHTML='<div class="h-full flex items-center justify-center text-sm" style="color:var(--text-muted)">Belum ada data</div>'; }

    // 6. Nilai per Kategori (Treemap)
    const nk=@json($nilaiPerKategori);
    if(nk.length>0){
        new ApexCharts(document.querySelector("#chartNilaiKat"),{...base,
            series:[{data:nk.map(k=>({x:k.nama,y:k.nilai}))}],
            chart:{...base.chart,type:'treemap',height:240},
            colors:['#6366f1','#06b6d4','#10b981','#f59e0b','#8b5cf6','#ec4899'],
            dataLabels:{style:{fontSize:'12px'}},
            plotOptions:{treemap:{distributed:true,enableShades:false}}
        }).render();
    } else { document.querySelector("#chartNilaiKat").innerHTML='<div class="h-full flex items-center justify-center text-sm" style="color:var(--text-muted)">Belum ada data</div>'; }
});
</script>
@endpush
