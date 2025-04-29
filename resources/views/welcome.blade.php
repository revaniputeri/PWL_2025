@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Hallo, apakabar!!!</h3>
        <h3 class="card-tools"></h3>
    </div>
    <div class="card-body">
        Selamat Datang semua, ini adalah halaman utama dari aplikasi ini.
        @auth
            <p>Anda login sebagai: {{ auth()->user()->nama }}</p>
        @endauth
    </div>
</div>
@endsection