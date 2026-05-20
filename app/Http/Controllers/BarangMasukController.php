<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function __construct(private InventoryService $inventoryService) {}

    public function index(Request $request)
    {
        $query = BarangMasuk::with('barang', 'user');

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

        return view('transaksi.masuk.index', compact('transaksis'));
    }

    public function create()
    {
        $barangs = Barang::where('is_active', true)->orderBy('nama')->get();
        return view('transaksi.masuk.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $this->inventoryService->prosesBarangMasuk($validated);
            return redirect()->route('barang-masuk.index')
                ->with('success', 'Transaksi barang masuk berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load('barang', 'user', 'batch');
        return view('transaksi.masuk.show', compact('barangMasuk'));
    }
}
