<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResenaProducto extends Model
{
    use HasFactory;

    protected $table = 'resenas_productos';

    protected $fillable = [
        'user_id',
        'producto_id',
        'puntuacion',
        'comentario',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
