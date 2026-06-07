<?php

return [
    // ── PDF Headers ──
    'laporan_persediaan' => 'INVENTORY REPORT',
    'laporan_transaksi' => 'TRANSACTION REPORT',
    'laporan_jurnal' => 'GENERAL JOURNAL',
    'laporan_laba_rugi' => 'INCOME STATEMENT',
    'laporan_neraca' => 'BALANCE SHEET',
    'laporan_perputaran' => 'INVENTORY TURNOVER REPORT',
    'laporan_barang_masuk' => 'STOCK IN REPORT',
    'laporan_barang_keluar' => 'STOCK OUT REPORT',

    // ── Common ──
    'tanggal' => 'Date',
    'periode' => 'Period',
    'per_tanggal' => 'As of',
    'sd' => 'to',
    'no' => 'No',
    'kode' => 'Code',
    'nama_barang' => 'Item Name',
    'kategori' => 'Category',
    'satuan' => 'Unit',
    'metode' => 'Method',
    'stok' => 'Stock',
    'harga_rata' => 'Avg. Price',
    'total_nilai' => 'Total Value',
    'status' => 'Status',
    'total' => 'Total',
    'grand_total' => 'Grand Total',
    'tidak_ada_data' => 'No data',
    'dicetak_oleh' => 'Printed by',
    'pada' => 'on',

    // ── Stok PDF ──
    'total_jenis' => 'Total Types:',
    'total_unit' => 'Total Units:',
    'barang' => 'items',

    // ── Transaksi PDF ──
    'total_masuk' => 'Total In:',
    'total_keluar' => 'Total Out:',
    'selisih' => 'Difference:',
    'barang_masuk' => 'STOCK IN',
    'barang_keluar' => 'STOCK OUT',
    'transaksi' => 'transactions',
    'kode_trx' => 'TRX Code',
    'supplier' => 'Supplier',
    'tujuan' => 'Destination',
    'jumlah' => 'Qty',
    'total_rp' => 'Total (Rp)',
    'total_hpp' => 'Total COGS (Rp)',
    'harga_satuan' => 'Unit Price',

    // ── Jurnal PDF ──
    'akun' => 'Account',
    'keterangan' => 'Description',
    'debit' => 'Debit (Rp)',
    'kredit' => 'Credit (Rp)',

    // ── Laba Rugi PDF ──
    'pendapatan' => 'REVENUE',
    'beban' => 'EXPENSES',
    'total_pendapatan' => 'Total Revenue',
    'total_beban' => 'Total Expenses',
    'laba_bersih' => 'NET PROFIT',
    'rugi_bersih' => 'NET LOSS',

    // ── Neraca PDF ──
    'aset' => 'ASSETS',
    'kewajiban' => 'LIABILITIES',
    'ekuitas' => 'EQUITY',
    'total_aset' => 'Total Assets',
    'total_kewajiban' => 'Total Liabilities',
    'total_ekuitas' => 'Total Equity',
    'kewajiban_ekuitas' => 'Liabilities + Equity',
    'seimbang' => '✓ BALANCE SHEET BALANCED',
    'tidak_seimbang' => '✗ UNBALANCED — Difference: Rp',
    'tidak_ada_kewajiban' => 'No liabilities',

    // ── Perputaran PDF ──
    'total_masuk_label' => 'Total In',
    'total_keluar_label' => 'Total Out',
    'rasio' => 'Ratio',

    // ── Excel Headings ──
    'excel.laporan_stok_title' => 'INVENTORY REPORT - Kedana Kedini',
    'excel.laporan_stok_sheet' => 'Stock Report',
    'excel.kode_barang' => 'Item Code',
    'excel.nama_barang' => 'Item Name',
    'excel.metode_stok' => 'Stock Method',
    'excel.harga_rata' => 'Average Price (Rp)',
    'excel.total_nilai' => 'Total Value (Rp)',
    'excel.safety_stock' => 'Safety Stock',
    'excel.reorder_point' => 'Reorder Point',

    'excel.masuk_title' => 'STOCK IN REPORT - Kedana Kedini',
    'excel.masuk_sheet' => 'Stock In',
    'excel.kode_transaksi' => 'Transaction Code',
    'excel.kode_barang_col' => 'Item Code',
    'excel.harga_satuan' => 'Unit Price (Rp)',
    'excel.total_harga' => 'Total Price (Rp)',
    'excel.petugas' => 'Officer',

    'excel.keluar_title' => 'STOCK OUT REPORT - Kedana Kedini',
    'excel.keluar_sheet' => 'Stock Out',
    'excel.harga_hpp' => 'COGS Price (Rp)',
    'excel.total_hpp' => 'Total COGS (Rp)',

    'excel.jurnal_sheet' => 'General Journal',
    'excel.nama_akun' => 'Account Name',
    'excel.tipe' => 'Type',
    'excel.saldo' => 'Balance',
    'excel.perputaran_sheet' => 'Inventory Turnover',
    'excel.rasio_perputaran' => 'Turnover Ratio',
    'excel.laba_rugi_sheet' => 'Income Statement',
    'excel.neraca_sheet' => 'Balance Sheet',
];
