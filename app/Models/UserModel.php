<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';        // mendefinisikan nama table dalam model ini
    protected $primaryKey = 'user_id';  // mendefinisikan primary key nya
    protected $fillable = ['username', 'nama', 'password', 'level_id'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }
    public function hasRole(string $role): bool
    {
        return $this->level->level_code === $role;
    }
    public function getRole()
    {
        return $this->level->level_code;
    }
}
