<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\StockNotifikasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus data lama jika ada
        User::truncate();
        Supplier::truncate();
        Barang::truncate();
        Transaksi::truncate();
        StockNotifikasi::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ───── CREATE USERS ─────
        $users = [
            [
                'name'     => 'Manager Utama',
                'email'    => 'manager@mart.com',
                'password' => Hash::make('password123'),
                'role'     => 'manager',
            ],
            [
                'name'     => 'Admin Gudang',
                'email'    => 'admin@mart.com',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Kasir Toko',
                'email'    => 'kasir@mart.com',
                'password' => Hash::make('password123'),
                'role'     => 'kasir',
            ],
        ];

        $createdUsers = [];
        foreach ($users as $user) {
            $createdUsers[] = User::create($user);
        }

        // ───── CREATE SUPPLIERS ─────
        $suppliers = [
            [
                'kode'         => 'SUP001',
                'nama'         => 'PT Mitra Jaya Utama',
                'email'        => 'contact@mitrajaya.com',
                'alamat'       => 'Jl. Industri No. 10, Jakarta',
                'telepon'      => '021-5555-0001',
                'jenis_barang' => 'Makanan & Minuman',
                'status'       => 'aktif',
                'user_id'      => $createdUsers[0]->id,
            ],
            [
                'kode'         => 'SUP002',
                'nama'         => 'CV Toko Elektronik Jaya',
                'email'        => 'sales@tokoelektronik.com',
                'alamat'       => 'Jl. Merdeka No. 45, Surabaya',
                'telepon'      => '031-7777-0002',
                'jenis_barang' => 'Elektronik',
                'status'       => 'aktif',
                'user_id'      => $createdUsers[0]->id,
            ],
            [
                'kode'         => 'SUP003',
                'nama'         => 'PT Baju Indah',
                'email'        => 'orders@bajuindah.id',
                'alamat'       => 'Jl. Ahmad Yani No. 20, Bandung',
                'telepon'      => '022-9999-0003',
                'jenis_barang' => 'Pakaian & Tekstil',
                'status'       => 'aktif',
                'user_id'      => $createdUsers[1]->id,
            ],
            [
                'kode'         => 'SUP004',
                'nama'         => 'Distributor Peralatan Rumah',
                'email'        => 'info@peralatanrumah.co.id',
                'alamat'       => 'Jl. Gatot Subroto No. 12, Medan',
                'telepon'      => '061-2222-0004',
                'jenis_barang' => 'Peralatan Rumah',
                'status'       => 'aktif',
                'user_id'      => $createdUsers[0]->id,
            ],
        ];

        $createdSuppliers = [];
        foreach ($suppliers as $supplier) {
            $createdSuppliers[] = Supplier::create($supplier);
        }

        // ───── CREATE BARANGS ─────
        $barangs = [
            // Dari SUP001 - Makanan & Minuman
            [
                'kode'        => 'BRG001',
                'nama'        => 'Minyak Goreng Kemasan 2L',
                'supplier_id' => $createdSuppliers[0]->id,
                'kategori'    => 'Minyak & Condiment',
                'harga_beli'  => 22000,
                'harga_jual'  => 28000,
                'stok'        => 45,
                'deskripsi'   => 'Minyak goreng berkualitas premium, tanpa kolesterol',
                'status'      => 'aktif',
            ],
            [
                'kode'        => 'BRG002',
                'nama'        => 'Gula Pasir 1kg',
                'supplier_id' => $createdSuppliers[0]->id,
                'kategori'    => 'Gula & Pemanis',
                'harga_beli'  => 11000,
                'harga_jual'  => 13500,
                'stok'        => 120,
                'deskripsi'   => 'Gula pasir putih, kemasan 1kg',
                'status'      => 'aktif',
            ],
            [
                'kode'        => 'BRG003',
                'nama'        => 'Beras Premium 5kg',
                'supplier_id' => $createdSuppliers[0]->id,
                'kategori'    => 'Beras',
                'harga_beli'  => 65000,
                'harga_jual'  => 82000,
                'stok'        => 25,
                'deskripsi'   => 'Beras premium grade A, kemasan 5kg',
                'status'      => 'aktif',
            ],
            [
                'kode'        => 'BRG004',
                'nama'        => 'Air Mineral 1.5L (Galon)',
                'supplier_id' => $createdSuppliers[0]->id,
                'kategori'    => 'Minuman',
                'harga_beli'  => 4000,
                'harga_jual'  => 5500,
                'stok'        => 200,
                'deskripsi'   => 'Air mineral kemasan galon 1.5 liter',
                'status'      => 'aktif',
            ],
            // Dari SUP002 - Elektronik
            [
                'kode'        => 'BRG005',
                'nama'        => 'Lampu LED 12W Putih',
                'supplier_id' => $createdSuppliers[1]->id,
                'kategori'    => 'Pencahayaan',
                'harga_beli'  => 28000,
                'harga_jual'  => 45000,
                'stok'        => 60,
                'deskripsi'   => 'Lampu LED hemat energi 12W, cahaya putih',
                'status'      => 'aktif',
            ],
            [
                'kode'        => 'BRG006',
                'nama'        => 'Powerbank 20000mAh',
                'supplier_id' => $createdSuppliers[1]->id,
                'kategori'    => 'Aksesori',
                'harga_beli'  => 120000,
                'harga_jual'  => 180000,
                'stok'        => 8,
                'deskripsi'   => 'Powerbank 20000mAh, 2 port USB',
                'status'      => 'aktif',
            ],
            [
                'kode'        => 'BRG007',
                'nama'        => 'Kabel Charger Micro USB',
                'supplier_id' => $createdSuppliers[1]->id,
                'kategori'    => 'Kabel',
                'harga_beli'  => 15000,
                'harga_jual'  => 25000,
                'stok'        => 3,
                'deskripsi'   => 'Kabel charger micro USB, panjang 2 meter',
                'status'      => 'aktif',
            ],
            // Dari SUP003 - Pakaian
            [
                'kode'        => 'BRG008',
                'nama'        => 'Kaos Polos M-XXL Putih',
                'supplier_id' => $createdSuppliers[2]->id,
                'kategori'    => 'Pakaian',
                'harga_beli'  => 35000,
                'harga_jual'  => 55000,
                'stok'        => 150,
                'deskripsi'   => 'Kaos polos 100% cotton, tersedia M-XXL',
                'status'      => 'aktif',
            ],
            [
                'kode'        => 'BRG009',
                'nama'        => 'Celana Jeans Pria',
                'supplier_id' => $createdSuppliers[2]->id,
                'kategori'    => 'Pakaian',
                'harga_beli'  => 120000,
                'harga_jual'  => 180000,
                'stok'        => 45,
                'deskripsi'   => 'Celana jeans pria, berbagai ukuran',
                'status'      => 'aktif',
            ],
            // Dari SUP004 - Peralatan
            [
                'kode'        => 'BRG010',
                'nama'        => 'Gelas Set 6 Pcs',
                'supplier_id' => $createdSuppliers[3]->id,
                'kategori'    => 'Peralatan Dapur',
                'harga_beli'  => 45000,
                'harga_jual'  => 65000,
                'stok'        => 22,
                'deskripsi'   => 'Set gelas ukir 6 pcs, bahan berkualitas',
                'status'      => 'aktif',
            ],
            [
                'kode'        => 'BRG011',
                'nama'        => 'Piring Set 12 Pcs',
                'supplier_id' => $createdSuppliers[3]->id,
                'kategori'    => 'Peralatan Dapur',
                'harga_beli'  => 80000,
                'harga_jual'  => 120000,
                'stok'        => 0,
                'deskripsi'   => 'Set piring 12 pcs, desain modern',
                'status'      => 'aktif',
            ],
        ];

        $createdBarangs = [];
        foreach ($barangs as $barang) {
            $createdBarangs[] = Barang::create($barang);
        }

        // ───── CREATE NOTIFIKASI STOK MINIMAL ─────
        $notifikasis = [
            [
                'barang_id'       => $createdBarangs[6]->id,
                'user_id'         => $createdUsers[1]->id,
                'tipe_notifikasi' => 'stok_minimal',
                'pesan'           => 'Stok Kabel Charger Micro USB tinggal 3 unit.',
                'dibaca'          => false,
            ],
            [
                'barang_id'       => $createdBarangs[10]->id,
                'user_id'         => $createdUsers[0]->id,
                'tipe_notifikasi' => 'stok_habis',
                'pesan'           => 'ALERT: Piring Set 12 Pcs STOK HABIS!',
                'dibaca'          => false,
            ],
        ];

        foreach ($notifikasis as $notifikasi) {
            StockNotifikasi::create($notifikasi);
        }

        // ───── DISPLAY INFO ─────
        $this->command->info('✅ Database seeded successfully!');
        
        $this->command->table(
            ['Type', 'Count', 'Info'],
            [
                ['Users', count($createdUsers), '3 default users with roles'],
                ['Suppliers', count($createdSuppliers), '4 suppliers assigned to users'],
                ['Barangs', count($createdBarangs), '11 products, 1 stok habis'],
                ['Notifikasi', count($notifikasis), '2 alerts (minimal & habis)'],
            ]
        );

        $this->command->info("\n📝 Login Credentials:");
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Manager', 'manager@mart.com', 'password123'],
                ['Admin', 'admin@mart.com', 'password123'],
                ['Kasir', 'kasir@mart.com', 'password123'],
            ]
        );

        $this->command->info("\n✨ Ready to use! Access: http://localhost:8000 or http://syabanmart.test");
    }
}
