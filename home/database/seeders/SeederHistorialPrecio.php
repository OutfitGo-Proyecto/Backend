<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HistorialPrecio;
use App\Models\Producto;
use App\Models\Tienda;

class SeederHistorialPrecio extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los productos con sus tiendas
        $productos = Producto::with('tiendas')->get();

        foreach ($productos as $producto) {
            foreach ($producto->tiendas as $tienda) {

                // Precio base actual del producto en esa tienda
                $precioActual = $tienda->pivot->precio;

                // Generar 3-5 puntos de historial hacia atrás en el tiempo
                $numPuntos = rand(3, 5);

                for ($i = 1; $i <= $numPuntos; $i++) {
                    // Variación aleatoria del precio (entre -20% y +20%)
                    $variacion = rand(-20, 20);
                    $precioHistorico = $precioActual + ($precioActual * ($variacion / 100));

                    // Fecha aleatoria hacia atrás (cada punto es un mes más antiguo aprox)
                    $fecha = now()->subMonths($i)->subDays(rand(0, 15));

                    HistorialPrecio::create([
                        'producto_id' => $producto->id,
                        'tienda_id' => $tienda->id,
                        'precio' => round($precioHistorico, 2),
                        'fecha_registro' => $fecha,
                    ]);
                }
            }
        }
    }
}
