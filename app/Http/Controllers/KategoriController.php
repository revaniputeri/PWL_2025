<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        // $data = [
        //     'kategori_id' => 6,
        //     'kategori_kode' => 'SNK',
        //     'kategori_nama' => 'Snack/Makanan Ringan'
        // ];
        // DB::table('m_kategori')->insert($data);
        // return 'Insert data baru berhasil';

        // $updatedRows = DB::table('m_kategori')
        //     ->where('kategori_nama', 'Snack/Makanan Ringan')
        //     ->update(['kategori_nama' => 'Camilan']);
        // return 'Update data berhasil, jumlah data yang diupdate: ' . $updatedRows . ' baris';

        // $deletedRows = DB::table('m_kategori')
        //     ->where('kategori_kode', 'SNK')
        //     ->delete();
        // return 'Data berhasil dihapus, jumlah data yang dihapus: ' . $deletedRows . ' baris';

        $data = DB::select('select * from m_kategori');
        return view('kategori', ['data' => $data]);
    }
}
