<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'nombre_en',
        'nombre_fr',
        'slug'
    ];

    protected $appends = ['nombre_localizado'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    /**
     * Obtiene el nombre de la categoría según el idioma actual.
     */
    public function getNombreLocalizadoAttribute()
    {
        $locale = app()->getLocale();
        $column = 'nombre_' . $locale;
        
        if ($locale !== 'es' && !empty($this->{$column})) {
            return $this->{$column};
        }
        
        return $this->nombre;
    }
}
