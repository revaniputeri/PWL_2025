<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $penjualanIds = range(1, 10); // sesuai dengan jml penjualan
        $barangIds = range(1, 10); // sesuai dengan jml barang

        $detailId = 1;
        foreach ($penjualanIds as $penjualanId) {
            $selectedBarang = array_rand(array_flip($barangIds), 3); //rand 3 barang tiap penjualan

            foreach ($selectedBarang as $barangId) {
                $hargaJual = DB::table('m_barang')->where('barang_id', $barangId)->value('harga_jual');
                $jumlah = rand(1, 5);

                $data[] = [
                    'detail_id' => $detailId++,
                    'penjualan_id' => $penjualanId,
                    'barang_id' => $barangId,
                    'harga' => $hargaJual,
                    'jumlah' => $jumlah,
                ];
            }
        }

        DB::table('t_penjualan_detail')->insert($data);
    }
}
