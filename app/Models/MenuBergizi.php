<?php

namespace App\Models;

use Database\Factories\MenuBergiziFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property string|null $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['nama', 'deskripsi', 'image'])]
class MenuBergizi extends Model
{
    /** @use HasFactory<MenuBergiziFactory> */
    use HasFactory;
}
