<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;

class TestBarang extends Command
{
    protected $signature = 'test:barang';
    protected $description = 'Test insert barang';

    public function handle(InventoryService $inventoryService)
    {
        \Illuminate\Support\Facades\Auth::loginUsingId(1);
        
        $validated = [
            'kode' => 'BRG-TEST-002',
            'nama' => 'Barang Test 2',
            'kategori_id' => null,
            'satuan' => 'pcs',
            'safety_stock' => 10,
            'lead_time' => 3,
            'pemakaian_rata_rata' => 5,
            'pemakaian_maksimum' => 10,
            'metode_stok' => 'fifo',
            'lokasi' => 'Gudang 1',
            'keterangan' => 'Test',
            'stok_awal' => 50,
            'harga_modal_awal' => 10000,
        ];
        
        try {
            DB::transaction(function () use ($validated, $inventoryService) {
                $barang = Barang::create(\Illuminate\Support\Arr::except($validated, ['stok_awal', 'harga_modal_awal']));
    
                if (!empty($validated['stok_awal']) && $validated['stok_awal'] > 0) {
                    if (empty($validated['harga_modal_awal'])) {
                        throw new \Exception('Harga Modal Awal harus diisi jika Stok Awal lebih dari 0.');
                    }
                    
                    // Catat stok awal sebagai transaksi Barang Masuk pertama
                    $inventoryService->prosesBarangMasuk([
                        'barang_id' => $barang->id,
                        'tanggal' => now()->format('Y-m-d'),
                        'jumlah' => $validated['stok_awal'],
                        'harga_satuan' => $validated['harga_modal_awal'],
                        'keterangan' => 'Pencatatan Stok Awal Persediaan',
                    ]);
                }
            });
            $this->info("Success");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
