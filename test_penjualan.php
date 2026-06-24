<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\JurnalEntry;
use App\Services\InventoryService;

Auth::loginUsingId(1);
$barang = Barang::where('stok', '>', 0)->first();
if (!$barang) {
    echo "Tidak ada barang dengan stok > 0.\n";
    exit;
}

$service = app(InventoryService::class);
$data = [
    'barang_id' => $barang->id,
    'tanggal' => now()->format('Y-m-d'),
    'jumlah' => 1,
    'harga_jual_satuan' => 50000,
    'tujuan' => 'Test Penjualan Script',
    'keterangan' => 'Testing script otomatis'
];

try {
    $keluar = $service->prosesBarangKeluar($data);
    echo "SUCCESS: Transaksi Barang Keluar Berhasil!\n";
    echo "Kode Transaksi: " . $keluar->kode_transaksi . "\n";
    echo "Barang: " . $barang->nama . "\n";
    echo "HPP (Harga Satuan): " . $keluar->harga_satuan . "\n";
    echo "Harga Jual: " . $keluar->harga_jual_satuan . "\n";
    
    echo "\n== Jurnal Entry yang Dihasilkan ==\n";
    $jurnals = JurnalEntry::where('referensi_tipe', 'barang_keluar')->where('referensi_id', $keluar->id)->get();
    foreach($jurnals as $j) {
        echo "- " . $j->akun->kode . " " . $j->akun->nama . " | Debit: " . $j->debit . " | Kredit: " . $j->kredit . "\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
