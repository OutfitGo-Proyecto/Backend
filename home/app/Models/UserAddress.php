<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'nombre_direccion',
        'direccion',
        'ciudad',
        'provincia',
        'codigo_postal',
        'telefono',
        'es_principal'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
