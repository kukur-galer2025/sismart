<?php

namespace Database\Seeders;

use App\Models\AkunKeuangan;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use App\Models\BarangMasuk;
use App\Models\BatchBarang;
use App\Models\JurnalEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@sismart.test',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@sismart.test',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        // 2. Kategori
        $elektronik = Kategori::create(['nama' => 'Elektronik', 'deskripsi' => 'Perangkat elektronik']);
        $atk = Kategori::create(['nama' => 'ATK', 'deskripsi' => 'Alat tulis kantor']);
        $furniture = Kategori::create(['nama' => 'Furniture', 'deskripsi' => 'Perabot kantor']);
        $cleaning = Kategori::create(['nama' => 'Cleaning', 'deskripsi' => 'Peralatan kebersihan']);

        // 3. Akun Keuangan (Chart of Accounts)
        $akuns = [
            ['kode'=>'1-000','nama'=>'Kas','tipe'=>'aset','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'1-100','nama'=>'Persediaan Barang','tipe'=>'aset','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'1-200','nama'=>'Piutang Usaha','tipe'=>'aset','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'2-000','nama'=>'Hutang Usaha','tipe'=>'kewajiban','saldo_normal'=>'kredit','saldo'=>0],
            ['kode'=>'3-000','nama'=>'Modal','tipe'=>'ekuitas','saldo_normal'=>'kredit','saldo'=>0],
            ['kode'=>'3-100','nama'=>'Prive','tipe'=>'ekuitas','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'4-000','nama'=>'Pendapatan Penjualan','tipe'=>'pendapatan','saldo_normal'=>'kredit','saldo'=>0],
            ['kode'=>'5-100','nama'=>'Harga Pokok Penjualan','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'5-200','nama'=>'Beban Operasional','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'5-300','nama'=>'Beban Listrik','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'5-400','nama'=>'Beban Air','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'5-500','nama'=>'Beban Gaji Karyawan','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
        ];
        
        $akunMap = [];
        foreach ($akuns as $akun) {
            $created = AkunKeuangan::create($akun);
            $akunMap[$created->kode] = $created;
        }

        // 4. Initial Capital Journal (Setoran Modal Awal)
        // Set initial capital so that Kas = 759,530,000. 
        // After inventory purchase of 259,530,000, Kas will be 500,000,000.
        $initialCapital = 759530000;
        $tanggalAwal = Carbon::create(2024, 1, 1);
        
        $kodeAwal = JurnalEntry::generateKode();
        JurnalEntry::create([
            'kode_jurnal' => $kodeAwal,
            'akun_id' => $akunMap['1-000']->id,
            'tanggal' => $tanggalAwal,
            'keterangan' => 'Setoran Modal Awal',
            'debit' => $initialCapital,
            'kredit' => 0,
            'user_id' => $admin->id
        ]);
        JurnalEntry::create([
            'kode_jurnal' => $kodeAwal,
            'akun_id' => $akunMap['3-000']->id,
            'tanggal' => $tanggalAwal,
            'keterangan' => 'Setoran Modal Awal',
            'debit' => 0,
            'kredit' => $initialCapital,
            'user_id' => $admin->id
        ]);
        $akunMap['1-000']->increment('saldo', $initialCapital);
        $akunMap['3-000']->increment('saldo', $initialCapital);

        // 5. Barang & Initial Inventory
        $items = [
            ['kode'=>'BRG-001','nama'=>'Laptop ASUS','kategori_id'=>$elektronik->id,'satuan'=>'unit','stok'=>25,'harga_rata_rata'=>8500000,'total_nilai'=>212500000,'safety_stock'=>5,'lead_time'=>7,'pemakaian_rata_rata'=>2,'pemakaian_maksimum'=>5,'metode_stok'=>'average'],
            ['kode'=>'BRG-002','nama'=>'Mouse Wireless','kategori_id'=>$elektronik->id,'satuan'=>'pcs','stok'=>50,'harga_rata_rata'=>150000,'total_nilai'=>7500000,'safety_stock'=>10,'lead_time'=>3,'pemakaian_rata_rata'=>5,'pemakaian_maksimum'=>10,'metode_stok'=>'fifo'],
            ['kode'=>'BRG-003','nama'=>'Kertas HVS A4','kategori_id'=>$atk->id,'satuan'=>'rim','stok'=>100,'harga_rata_rata'=>45000,'total_nilai'=>4500000,'safety_stock'=>20,'lead_time'=>2,'pemakaian_rata_rata'=>10,'pemakaian_maksimum'=>20,'metode_stok'=>'average'],
            ['kode'=>'BRG-004','nama'=>'Pulpen Pilot','kategori_id'=>$atk->id,'satuan'=>'lusin','stok'=>30,'harga_rata_rata'=>36000,'total_nilai'=>1080000,'safety_stock'=>5,'lead_time'=>2,'pemakaian_rata_rata'=>3,'pemakaian_maksimum'=>7,'metode_stok'=>'average'],
            ['kode'=>'BRG-005','nama'=>'Meja Kerja','kategori_id'=>$furniture->id,'satuan'=>'unit','stok'=>10,'harga_rata_rata'=>1200000,'total_nilai'=>12000000,'safety_stock'=>2,'lead_time'=>14,'pemakaian_rata_rata'=>1,'pemakaian_maksimum'=>3,'metode_stok'=>'fifo'],
            ['kode'=>'BRG-006','nama'=>'Kursi Kantor','kategori_id'=>$furniture->id,'satuan'=>'unit','stok'=>15,'harga_rata_rata'=>850000,'total_nilai'=>12750000,'safety_stock'=>3,'lead_time'=>14,'pemakaian_rata_rata'=>1,'pemakaian_maksimum'=>4,'metode_stok'=>'fifo'],
            ['kode'=>'BRG-007','nama'=>'Sabun Pel','kategori_id'=>$cleaning->id,'satuan'=>'botol','stok'=>8,'harga_rata_rata'=>25000,'total_nilai'=>200000,'safety_stock'=>10,'lead_time'=>2,'pemakaian_rata_rata'=>4,'pemakaian_maksimum'=>8,'metode_stok'=>'average'],
            ['kode'=>'BRG-008','nama'=>'Keyboard Mechanical','kategori_id'=>$elektronik->id,'satuan'=>'pcs','stok'=>20,'harga_rata_rata'=>450000,'total_nilai'=>9000000,'safety_stock'=>5,'lead_time'=>5,'pemakaian_rata_rata'=>2,'pemakaian_maksimum'=>5,'metode_stok'=>'average'],
        ];

        foreach ($items as $index => $itemData) {
            $stok = $itemData['stok'];
            $harga = $itemData['harga_rata_rata'];
            $totalNilai = $stok * $harga;

            // Save barang without stock first to mimic fresh creation
            $itemData['stok'] = 0;
            $itemData['total_nilai'] = 0;
            $barang = Barang::create($itemData);

            // Simulating Barang Masuk
            $masuk = BarangMasuk::create([
                'kode_transaksi' => 'BM-202401' . sprintf('%04d', $index + 1),
                'barang_id' => $barang->id,
                'user_id' => $admin->id,
                'tanggal' => $tanggalAwal,
                'jumlah' => $stok,
                'harga_satuan' => $harga,
                'total_harga' => $totalNilai,
                'supplier' => 'Supplier Awal',
                'keterangan' => 'Saldo Awal Barang',
            ]);

            // Create Batch
            BatchBarang::create([
                'barang_id' => $barang->id,
                'barang_masuk_id' => $masuk->id,
                'jumlah_awal' => $stok,
                'jumlah_sisa' => $stok,
                'harga_satuan' => $harga,
                'tanggal_masuk' => $tanggalAwal,
            ]);

            // Update Barang Stock
            $barang->update([
                'stok' => $stok,
                'total_nilai' => $totalNilai
            ]);

            // Jurnal Barang Masuk (Debit Persediaan, Kredit Kas)
            $kodeJurnalBrg = JurnalEntry::generateKode();
            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnalBrg,
                'akun_id' => $akunMap['1-100']->id,
                'tanggal' => $tanggalAwal,
                'keterangan' => "Pembelian Awal " . $barang->nama,
                'debit' => $totalNilai,
                'kredit' => 0,
                'referensi_tipe' => 'barang_masuk',
                'referensi_id' => $masuk->id,
                'user_id' => $admin->id
            ]);
            JurnalEntry::create([
                'kode_jurnal' => $kodeJurnalBrg,
                'akun_id' => $akunMap['1-000']->id,
                'tanggal' => $tanggalAwal,
                'keterangan' => "Pembelian Awal " . $barang->nama,
                'debit' => 0,
                'kredit' => $totalNilai,
                'referensi_tipe' => 'barang_masuk',
                'referensi_id' => $masuk->id,
                'user_id' => $admin->id
            ]);

            $akunMap['1-100']->increment('saldo', $totalNilai);
            $akunMap['1-000']->decrement('saldo', $totalNilai);
        }
    }
}
