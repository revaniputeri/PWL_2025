<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;
    protected $table = 'm_kategori'; //mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'kategori_id'; //mendefinisikan primary keyd ari tabel yang digunakan
    protected $fillable = ['kategori_kode', 'kategori_nama'];
}
