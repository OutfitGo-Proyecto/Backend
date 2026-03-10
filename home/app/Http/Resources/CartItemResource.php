<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cantidad' => $this->cantidad,
            'subtotal' => $this->cantidad * ($this->producto ? $this->producto->precio : 0),
            'producto' => [
                'id' => $this->producto->id ?? null,
                'nombre' => $this->producto->nombre ?? null,
                'slug' => $this->producto->slug ?? null,
                'precio' => $this->producto->precio ?? null,
                'url_imagen_principal' => $this->producto->url_imagen_principal ?? null,
                'stock' => $this->producto->stock ?? 0,
            ],
            'creado_en' => $this->created_at,
            'actualizado_en' => $this->updated_at,
        ];
    }
}
