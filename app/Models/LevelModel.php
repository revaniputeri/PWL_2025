<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    protected $table = 'm_level'; //mendefinisikan nama tabel yangdigunakan oleh model ini
    protected $primaryKey = 'level_id'; //mendefinisikan primary keydari tabel yang digunakan
    protected $fillable = ['level_code', 'level_nama'];
}
