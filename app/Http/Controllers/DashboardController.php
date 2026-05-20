<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary statistics
        $totalBarang = Barang::where('is_active', true)->count();
        $totalStok = Barang::where('is_active', true)->sum('stok');
        $nilaiPersediaan = Barang::where('is_active', true)->sum('total_nilai');
        $stokKritis = Barang::where('is_active', true)
            ->whereColumn('stok', '<=', 'safety_stock')
            ->count();

        // Items needing reorder
        $barangReorder = Barang::where('is_active', true)
            ->whereRaw('stok <= (pemakaian_rata_rata * lead_time + safety_stock)')
            ->orderBy('stok')
            ->limit(10)
            ->get();

        // Recent transactions
        $recentMasuk = BarangMasuk::with('barang', 'user')
            ->latest('tanggal')
            ->limit(5)
            ->get();

        $recentKeluar = BarangKeluar::with('barang', 'user')
            ->latest('tanggal')
            ->limit(5)
            ->get();

        // Monthly chart data (last 6 months)
        $chartData = $this->getChartData();

        // Stock by category
        $stockByCategory = Kategori::withSum('barangs', 'stok')
            ->withSum('barangs', 'total_nilai')
            ->get();

        // Top 5 items by stock value
        $topItems = Barang::where('is_active', true)
            ->orderByDesc('total_nilai')
            ->limit(5)
            ->get(['nama', 'total_nilai', 'stok', 'satuan']);

        // Stock status distribution
        $allBarangs = Barang::where('is_active', true)->get();
        $stockStatus = [
            'Aman' => $allBarangs->filter(fn($b) => $b->status_stok === 'Aman')->count(),
            'Reorder' => $allBarangs->filter(fn($b) => $b->status_stok === 'Reorder')->count(),
            'Kritis' => $allBarangs->filter(fn($b) => $b->status_stok === 'Kritis')->count(),
            'Habis' => $allBarangs->filter(fn($b) => $b->status_stok === 'Habis')->count(),
        ];

        // Monthly transaction count (volume, not value)
        $txCountData = $this->getTxCountData();

        // Nilai persediaan per kategori
        $nilaiPerKategori = $stockByCategory->map(fn($k) => [
            'nama' => $k->nama,
            'nilai' => (float) ($k->barangs_sum_total_nilai ?? 0),
        ])->filter(fn($k) => $k['nilai'] > 0)->values();

        // Notifications
        $notifications = $this->getNotifications();

        return view('dashboard', compact(
            'totalBarang', 'totalStok', 'nilaiPersediaan', 'stokKritis',
            'barangReorder', 'recentMasuk', 'recentKeluar',
            'chartData', 'stockByCategory', 'notifications',
            'topItems', 'stockStatus', 'txCountData', 'nilaiPerKategori'
        ));
    }

    private function getChartData(): array
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        $masukData = BarangMasuk::select(
            DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('tanggal', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $keluarData = BarangKeluar::select(
            DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('tanggal', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        return [
            'labels' => $months->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->translatedFormat('M Y'))->toArray(),
            'masuk' => $months->map(fn($m) => (float) ($masukData[$m] ?? 0))->toArray(),
            'keluar' => $months->map(fn($m) => (float) ($keluarData[$m] ?? 0))->toArray(),
        ];
    }

    private function getTxCountData(): array
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        $masukCount = BarangMasuk::select(
            DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
            DB::raw('COUNT(*) as total')
        )
            ->where('tanggal', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $keluarCount = BarangKeluar::select(
            DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
            DB::raw('COUNT(*) as total')
        )
            ->where('tanggal', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        return [
            'labels' => $months->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->translatedFormat('M'))->toArray(),
            'masuk' => $months->map(fn($m) => (int) ($masukCount[$m] ?? 0))->toArray(),
            'keluar' => $months->map(fn($m) => (int) ($keluarCount[$m] ?? 0))->toArray(),
        ];
    }

    private function getNotifications(): array
    {
        $notifications = [];

        // Stock depleted
        $habis = Barang::where('is_active', true)->where('stok', 0)->get();
        foreach ($habis as $b) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'exclamation-triangle',
                'message' => "Stok {$b->nama} sudah HABIS!",
                'time' => now(),
            ];
        }

        // Critical stock (below safety stock)
        $kritis = Barang::where('is_active', true)
            ->where('stok', '>', 0)
            ->whereColumn('stok', '<=', 'safety_stock')
            ->get();
        foreach ($kritis as $b) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'exclamation-circle',
                'message' => "Stok {$b->nama} kritis! Tersisa: {$b->stok} {$b->satuan}",
                'time' => now(),
            ];
        }

        // Reorder point reached
        $reorder = Barang::where('is_active', true)
            ->where('stok', '>', 0)
            ->whereColumn('stok', '>', 'safety_stock')
            ->whereRaw('stok <= (pemakaian_rata_rata * lead_time + safety_stock)')
            ->get();
        foreach ($reorder as $b) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'info-circle',
                'message' => "Stok {$b->nama} mencapai Reorder Point. Segera lakukan pemesanan.",
                'time' => now(),
            ];
        }

        return $notifications;
    }
}
