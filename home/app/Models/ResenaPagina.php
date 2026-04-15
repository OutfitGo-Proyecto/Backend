<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResenaPagina extends Model
{
    use HasFactory;

    protected $table = 'resenas_pagina';

    protected $fillable = [
        'user_id',
        'puntuacion',
        'comentario',
        'visible_en_portada',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
