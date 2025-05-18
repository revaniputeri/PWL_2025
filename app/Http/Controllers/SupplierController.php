<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SupplierController extends Controller
{
    // Method index untuk menampilkan halaman utama level
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];

        $activeMenu = 'supplier';

        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Method create untuk menampilkan form tambah supplier
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];

        $activeMenu = 'supplier'; // Set menu yang sedang aktif

        return view('supplier.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'nullable|string|max:255',
            'supplier_telp' => 'nullable|string|max:20',
            'supplier_email' => 'nullable|email|max:100',
            'supplier_kontak' => 'nullable|string|max:100',
        ]);

        // Generate kode supplier otomatis
        $lastSupplier = SupplierModel::orderBy('supplier_id', 'desc')->first();
        $lastKode = $lastSupplier ? intval(substr($lastSupplier->supplier_kode, 3)) : 0;
        $newKode = 'SUP' . str_pad($lastKode + 1, 3, '0', STR_PAD_LEFT);

        try {
            SupplierModel::create([
                'supplier_kode' => $newKode,
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat,
                'supplier_telp' => $request->supplier_telp,
                'supplier_email' => $request->supplier_email,
                'supplier_kontak' => $request->supplier_kontak,
            ]);

            return redirect('/supplier')->with('success', 'Supplier berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function list(Request $request)
    {
        $suppliers = SupplierModel::select(
            'supplier_id',
            'supplier_kode',
            'supplier_nama',
            'supplier_alamat'
        );

        return DataTables::of($suppliers)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                $btn = '';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/show_ajax') . '\')" class="btn btn-info btn-sm mr-1">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mr-1">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = SupplierModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail supplier'
        ];

        $activeMenu = 'supplier'; // Set menu yang sedang aktif

        return view('supplier.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'supplier' => $supplier
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = SupplierModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit supplier'
        ];

        $activeMenu = 'supplier'; // Set menu yang sedang aktif

        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'supplier' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'nullable|string|max:255'
        ]);

        $supplier = SupplierModel::findOrFail($id);

        try {
            $supplier->update([
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat
            ]);

            return redirect('/supplier')->with('success', 'Supplier berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supplier = SupplierModel::findOrFail($id);
            $supplier->delete();

            return redirect('/supplier')->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect('/supplier')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // membuat dan menampilkan halaman form tambah supplier dgn Ajax
    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        Log::info('store_ajax called with data:', $request->all());

        if ($request->ajax()) {
            $rules = [
                'supplier_nama' => 'required|string|min:3|max:100',
                'supplier_alamat' => 'required|string|min:5'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                // Generate kode supplier otomatis
                $lastSupplier = SupplierModel::orderBy('supplier_id', 'desc')->first();
                $lastKode = $lastSupplier ? intval(substr($lastSupplier->supplier_kode ?? 'SUP000', 3)) : 0;
                $newKode = 'SUP' . str_pad($lastKode + 1, 3, '0', STR_PAD_LEFT);

                // Buat supplier baru
                $supplier = new SupplierModel();
                $supplier->supplier_kode = $newKode;
                $supplier->supplier_nama = $request->supplier_nama;
                $supplier->supplier_alamat = $request->supplier_alamat;

                $supplier->save();

                Log::info('Supplier created successfully with ID: ' . $supplier->supplier_id);

                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil ditambahkan'
                ]);
            } catch (\Exception $e) {
                Log::error('Error creating supplier: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/supplier');
    }

    public function edit_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax()) {
            $rules = [
                'supplier_kode' => 'required|string|max:10',
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                $supplier = SupplierModel::find($id);
                if (!$supplier) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data supplier tidak ditemukan'
                    ]);
                }

                $supplier->supplier_kode = $request->supplier_kode;
                $supplier->supplier_nama = $request->supplier_nama;
                $supplier->supplier_alamat = $request->supplier_alamat;
                $supplier->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil diperbarui'
                ]);
            } catch (\Exception $e) {
                Log::error('Error updating supplier: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/supplier');
    }

    public function confirm_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax()) {
            try {
                $supplier = SupplierModel::find($id);
                if (!$supplier) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data supplier tidak ditemukan'
                    ]);
                }

                $supplier->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                Log::error('Error deleting supplier: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/supplier');
    }

    public function show_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.show_ajax', ['supplier' => $supplier]);
    }

    /**
     * Menampilkan form import supplier
     */
    public function import()
    {
        return view('supplier.import');
    }

    /**
     * Mengimport data supplier dari file excel
     */
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_supplier');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header
                        $insert[] = [
                            'supplier_kode' => $value['A'],
                            'supplier_nama' => $value['B'],
                            'supplier_alamat' => $value['C'],
                        ];
                    }
                }

                if (count($insert) > 0) {
                    SupplierModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }

        return redirect('/supplier');
    }

    /**
     * Export supplier data to Excel
     */
    public function export_excel()
    {
        // Get supplier data
        $suppliers = SupplierModel::all();

        // Load PhpSpreadsheet library
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Alamat');
        $sheet->setCellValue('E1', 'Telepon');
        $sheet->setCellValue('F1', 'Email');
        $sheet->setCellValue('G1', 'Kontak');
        $sheet->setCellValue('H1', 'Tgl Dibuat');

        // Make headers bold
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);

        // Fill data
        $row = 2;
        $no = 1;
        foreach ($suppliers as $supplier) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $supplier->supplier_kode);
            $sheet->setCellValue('C' . $row, $supplier->supplier_nama);
            $sheet->setCellValue('D' . $row, $supplier->supplier_alamat);
            $sheet->setCellValue('E' . $row, $supplier->supplier_telp);
            $sheet->setCellValue('F' . $row, $supplier->supplier_email);
            $sheet->setCellValue('G' . $row, $supplier->supplier_kontak);
            $sheet->setCellValue('H' . $row, $supplier->created_at);
            $row++;
        }

        // Create Excel writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set headers to download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data_Supplier_' . date('YmdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Save to output
        $writer->save('php://output');
        exit;
    }
}
