<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function index()
    {
        // DB::insert('insert into m_level (level_id, level_code, level_nama, created_at) values (?, ?, ?, ?)', [4, 'CUS', 'Pelanggan', now()]);
        // return 'Insert data baru berhasil';

        // $row = DB::delete('delete from m_level where level_code = ?', ['CUS']);
        // return 'Data berhasil dihapus, data yang berhasil dihapus sebanyak ' . $row . ' baris';

        $data = DB::select('select * from m_level');
        return view('level', ['data' => $data]);
    }
}
