<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Producto;

class Favorite extends Model
{
    /** @use HasFactory<\Database\Factories\FavoriteFactory> */
    use HasFactory;

    // Campos que permitimos llenar masivamente
    protected $fillable = ['user_id', 'producto_id'];

    // Relación con el usuario que marcó el favorito
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el producto marcado
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
