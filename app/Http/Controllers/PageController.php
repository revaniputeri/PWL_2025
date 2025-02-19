<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return 'Selamat Datang';
    }

    public function about()
    {
        return '2341760056 - Revani Nanda Putri';
    }

    public function articles($articlesId)
    {
        return 'Halaman Artikel dengan Id ' . $articlesId;
    }
}
