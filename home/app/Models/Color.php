<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'hex_code'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_color');
    }
}
