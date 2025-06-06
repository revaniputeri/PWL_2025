@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        @empty($supplier)
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                Data yang anda cari tidak ditemukan.
            </div>
            <a href="{{ url('supplier') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        @else
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Kode supplier</label>
                <div class="col-12">
                    <input type="text" class="form-control" id="supplier_kode" name="supplier_kode" 
                        value="{{ old('supplier_kode', $supplier->supplier_kode) }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Nama supplier</label>
                <div class="col-12">
                    <input type="text" class="form-control" id="supplier_nama" name="supplier_nama" 
                        value="{{ old('supplier_nama', $supplier->supplier_nama) }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Alamat supplier</label>
                <div class="col-12">
                    <input type="text" class="form-control" id="supplier_alamat" name="supplier_alamat" 
                        value="{{ old('supplier_alamat', $supplier->supplier_alamat) }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-12">
                    <a class="btn btn-sm btn-default" href="{{ url('supplier') }}">Kembali</a>
                </div>
            </div>
        @endempty
    </div>
</div>
@endsection
@push('css')
@endpush
@push('js')
@endpush