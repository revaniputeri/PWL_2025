<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // Import the Validator class
use Illuminate\Support\Facades\Log; // Import the Log facade
use PhpOffice\PhpSpreadsheet\IOFactory; // Import IOFactory for Excel handling

class LevelController extends Controller
{
    public function index()
    {
        $level = LevelModel::all(); // ambil data level untukditampilkan di form
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];
        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];
        $activeMenu = 'level'; // set menu yang aktif
        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }
    // ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_nama', 'level_code');
        return DataTables::of($levels)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = ''; // Inisialisasi variabel $btn
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    // Menampilkan halaman form tambah level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah level baru'
        ];
        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'level'; // set menu yang aktif
        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'level_code' => 'required|string|min:3',
            'level_nama' => 'required|string'
        ]);
        LevelModel::create([
            'level_code' => $request->level_code,
            'level_nama' => $request->level_nama
        ]);
        return redirect('/level')->with('status', 'Data level
    berhasil ditambahkan');
    }
    public function show(string $id)
    {
        $level = LevelModel::find($id); // ambil data level untuk ditampilkan di form
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail level'
        ];
        $activeMenu = 'level'; // set menu yang aktif
        return view('level.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    public function edit($id)
    {
        $level = LevelModel::findOrFail($id); // Ambil data level berdasarkan ID
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit level'
        ];
        $activeMenu = 'level'; // Set menu yang sedang aktif
        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level // Kirim data level ke view
        ]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_code' => 'required|string|min:3',
            'level_nama' => 'required|string'
        ]);
        LevelModel::find($id)->update([
            'level_code' => $request->level_code,
            'level_nama' => $request->level_nama
        ]);
        return redirect('/level')->with('status', 'Data level
    berhasil diubah');
    }
    public function destroy(string $id)
    {
        $check = LevelModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level
    tidak ditemukan');
        }
        try {
            LevelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level
    berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data level
    tidak bisa dihapus karena masih digunakan pada tabel lain');
        }
    }

    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    // menyimpan data level baru dengan ajax
    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax()) {
            $rules = [
                'level_code' => 'required|string|min:2|max:10|unique:m_level,level_code',
                'level_nama' => 'required|string|min:3|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            LevelModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil ditambahkan'
            ]);
        }

        return redirect('/');
    }

    // menampilkan halaman form edit level dengan ajax
    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }

    // menyimpan perubahan data level dengan ajax
    public function update_ajax(Request $request, string $id)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_code' => 'required|string|min:2|max:10|unique:m_level,level_code,' . $id . ',level_id',
                'level_nama' => 'required|string|min:3|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = LevelModel::find($id);

            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    // menampilkan konfirmasi hapus level dengan ajax
    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    // menghapus data level dengan ajax
    public function delete_ajax(Request $request, string $id)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);

            if ($level) {
                try {
                    $level->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terkait dengan data lain'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    /**
     * Show the form for importing levels
     */
    public function import()
    {
        return view('level.import');
    }

    /**
     * Import levels from Excel file
     */
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_level' => ['required', 'mimes:xlsx,xls', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                $file = $request->file('file_level');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true);

                $insert = [];
                if (count($data) > 1) {
                    foreach ($data as $row => $value) {
                        if ($row > 1) { // Skip header row
                            // Fixed column name to match database schema: level_code instead of level_kode
                            $insert[] = [
                                'level_code' => $value['A'],
                                'level_nama' => $value['B'],
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                    }
                    if (count($insert) > 0) {
                        LevelModel::insertOrIgnore($insert);
                        return response()->json([
                            'status' => true,
                            'message' => 'Data level berhasil diimport'
                        ]);
                    }
                }
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            } catch (\Exception $e) {
                Log::error('Level Import Error: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengunggah file: ' . $e->getMessage()
                ], 500);
            }
        }
        return redirect('/level');
    }

    /**
     * Export level data to Excel
     */
    public function export_excel()
    {
        // Get level data
        $levels = LevelModel::orderBy('level_id')->get();

        // Load PhpSpreadsheet library
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Tgl Dibuat');

        // Make headers bold
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);

        // Fill data
        $row = 2;
        $no = 1;
        foreach ($levels as $level) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $level->level_code);
            $sheet->setCellValue('C' . $row, $level->level_nama);
            $sheet->setCellValue('D' . $row, $level->created_at);
            $row++;
        }

        // Create Excel writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set headers to download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data_Level_' . date('YmdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Save to output
        $writer->save('php://output');
        exit;
    }
}
