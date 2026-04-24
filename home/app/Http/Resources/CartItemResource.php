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
            'subtotal' => $this->cantidad * ($this->variante?->producto?->precio ?? 0),
            'variante' => [
                'id' => $this->producto_variante_id,
                'stock' => $this->variante?->stock ?? 0,
                'talla' => [
                    'nombre' => $this->variante?->talla?->nombre ?? null,
                ],
                'color' => [
                    'nombre' => $this->variante?->color?->nombre ?? null,
                ],
                'producto' => [
                    'id' => $this->variante?->producto?->id ?? null,
                    'nombre' => $this->variante?->producto?->nombre ?? null,
                    'slug' => $this->variante?->producto?->slug ?? null,
                    'precio' => $this->variante?->producto?->precio ?? null,
                    'url_imagen_principal' => $this->variante?->producto?->url_imagen_principal ?? null,
                ]
            ],
            'creado_en' => $this->created_at,
            'actualizado_en' => $this->updated_at,
        ];
    }
}
