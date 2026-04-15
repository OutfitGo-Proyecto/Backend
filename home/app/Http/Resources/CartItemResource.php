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
            'subtotal' => $this->cantidad * ($this->variante?->producto?->precio ?? ($this->producto?->precio ?? 0)),
            'producto' => [
                'id' => $this->variante?->producto?->id ?? ($this->producto?->id ?? null),
                'nombre' => $this->variante?->producto?->nombre ?? ($this->producto?->nombre ?? null),
                'slug' => $this->variante?->producto?->slug ?? ($this->producto?->slug ?? null),
                'precio' => $this->variante?->producto?->precio ?? ($this->producto?->precio ?? null),
                'url_imagen_principal' => $this->variante?->producto?->url_imagen_principal ?? ($this->producto?->url_imagen_principal ?? null),
                'stock' => $this->variante?->stock ?? ($this->producto?->stock ?? 0),
                'color' => $this->variante?->color?->nombre ?? null,
                'talla' => $this->variante?->talla?->nombre ?? null,
            ],
            'creado_en' => $this->created_at,
            'actualizado_en' => $this->updated_at,
        ];
    }
}
