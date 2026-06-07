<?php

namespace Database\Seeders;

use App\Models\AkunKeuangan;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@sismart.test',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
        ]);
        User::create([
            'name' => 'Manager',
            'email' => 'manager@sismart.test',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        // Kategori
        $elektronik = Kategori::create(['nama' => 'Elektronik', 'deskripsi' => 'Perangkat elektronik']);
        $atk = Kategori::create(['nama' => 'ATK', 'deskripsi' => 'Alat tulis kantor']);
        $furniture = Kategori::create(['nama' => 'Furniture', 'deskripsi' => 'Perabot kantor']);
        $cleaning = Kategori::create(['nama' => 'Cleaning', 'deskripsi' => 'Peralatan kebersihan']);

        // Barang
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
        foreach ($items as $item) {
            Barang::create($item);
        }

        // Akun Keuangan (Chart of Accounts)
        $akuns = [
            ['kode'=>'1-000','nama'=>'Kas','tipe'=>'aset','saldo_normal'=>'debit','saldo'=>500000000],
            ['kode'=>'1-100','nama'=>'Persediaan Barang','tipe'=>'aset','saldo_normal'=>'debit','saldo'=>259530000],
            ['kode'=>'1-200','nama'=>'Piutang Usaha','tipe'=>'aset','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'2-000','nama'=>'Hutang Usaha','tipe'=>'kewajiban','saldo_normal'=>'kredit','saldo'=>0],
            ['kode'=>'3-000','nama'=>'Modal','tipe'=>'ekuitas','saldo_normal'=>'kredit','saldo'=>759530000],
            ['kode'=>'3-100','nama'=>'Prive','tipe'=>'ekuitas','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'4-000','nama'=>'Pendapatan Penjualan','tipe'=>'pendapatan','saldo_normal'=>'kredit','saldo'=>0],
            ['kode'=>'5-100','nama'=>'Harga Pokok Penjualan','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'5-200','nama'=>'Beban Operasional','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'5-300','nama'=>'Beban Listrik','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'5-400','nama'=>'Beban Air','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
            ['kode'=>'5-500','nama'=>'Beban Gaji Karyawan','tipe'=>'beban','saldo_normal'=>'debit','saldo'=>0],
        ];
        foreach ($akuns as $akun) {
            AkunKeuangan::create($akun);
        }
    }
}
