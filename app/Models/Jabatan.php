<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama'])]
class Jabatan extends Model
{
    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }
}
