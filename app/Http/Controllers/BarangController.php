<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function __construct(private InventoryService $inventoryService) {}

    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'habis' => $query->where('stok', 0),
                'kritis' => $query->where('stok', '>', 0)->whereColumn('stok', '<=', 'safety_stock'),
                'aman' => $query->whereColumn('stok', '>', 'safety_stock'),
                default => null,
            };
        }

        $barangs = $query->latest()->paginate(15)->withQueryString();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('barang.index', compact('barangs', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();
        return view('barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:barangs,kode',
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'satuan' => 'required|string|max:50',
            'safety_stock' => 'required|integer|min:0',
            'lead_time' => 'required|integer|min:1',
            'pemakaian_rata_rata' => 'required|integer|min:0',
            'pemakaian_maksimum' => 'required|integer|min:0',
            'metode_stok' => 'required|in:fifo,average',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'stok_awal' => 'nullable|integer|min:0',
            'harga_modal_awal' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $barang = Barang::create(\Illuminate\Support\Arr::except($validated, ['stok_awal', 'harga_modal_awal']));

            if (!empty($validated['stok_awal']) && $validated['stok_awal'] > 0) {
                if (empty($validated['harga_modal_awal'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'harga_modal_awal' => 'Harga Modal Awal harus diisi jika Stok Awal lebih dari 0.'
                    ]);
                }
                
                // Catat stok awal sebagai transaksi Barang Masuk pertama
                $this->inventoryService->prosesBarangMasuk([
                    'barang_id' => $barang->id,
                    'tanggal' => now()->format('Y-m-d'),
                    'jumlah' => $validated['stok_awal'],
                    'harga_satuan' => $validated['harga_modal_awal'],
                    'keterangan' => 'Pencatatan Stok Awal Persediaan',
                ]);
            }
        });

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'barangMasuks' => fn($q) => $q->latest('tanggal')->limit(10),
                        'barangKeluars' => fn($q) => $q->latest('tanggal')->limit(10),
                        'allBatches']);

        $rop = $barang->hitungReorderPoint();
        $safetyStockCalc = $barang->hitungSafetyStock();
        $perputaran = $barang->hitungPerputaran();

        return view('barang.show', compact('barang', 'rop', 'safetyStockCalc', 'perputaran'));
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::orderBy('nama')->get();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:barangs,kode,' . $barang->id,
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'satuan' => 'required|string|max:50',
            'safety_stock' => 'required|integer|min:0',
            'lead_time' => 'required|integer|min:1',
            'pemakaian_rata_rata' => 'required|integer|min:0',
            'pemakaian_maksimum' => 'required|integer|min:0',
            'metode_stok' => 'required|in:fifo,average',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $barang->update($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
