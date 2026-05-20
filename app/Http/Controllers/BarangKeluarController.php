<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function __construct(private InventoryService $inventoryService) {}

    public function index(Request $request)
    {
        $query = BarangKeluar::with('barang', 'user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhereHas('barang', fn($q) => $q->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        $transaksis = $query->latest('tanggal')->paginate(15)->withQueryString();

        return view('transaksi.keluar.index', compact('transaksis'));
    }

    public function create()
    {
        $barangs = Barang::where('is_active', true)->where('stok', '>', 0)->orderBy('nama')->get();
        return view('transaksi.keluar.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'tujuan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $this->inventoryService->prosesBarangKeluar($validated);
            return redirect()->route('barang-keluar.index')
                ->with('success', 'Transaksi barang keluar berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load('barang', 'user');
        return view('transaksi.keluar.show', compact('barangKeluar'));
    }
}
