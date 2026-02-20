<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Tienda;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear un Usuario de prueba (para que puedas loguearte tú luego)
        User::factory()->create([
            'name' => 'Admin OutfitGo',
            'email' => 'admin@outfitgo.com',
            'password' => bcrypt('password'), // La contraseña es "password"
        ]);

        // 2. Crear Categorías Reales (para que el menú se vea bien)
        $catZapatillas = Categoria::factory()->create(['nombre' => 'Zapatillas', 'slug' => 'zapatillas']);
        $catSudaderas = Categoria::factory()->create(['nombre' => 'Sudaderas', 'slug' => 'sudaderas']);
        $catAccesorios = Categoria::factory()->create(['nombre' => 'Accesorios', 'slug' => 'accesorios']);

        // 3. Crear Marcas Reales
        $marcas = collect();
        $marcas->push(Marca::factory()->create(['nombre' => 'Nike', 'slug' => 'nike']));
        $marcas->push(Marca::factory()->create(['nombre' => 'Adidas', 'slug' => 'adidas']));
        $marcas->push(Marca::factory()->create(['nombre' => 'Puma', 'slug' => 'puma']));
        $marcas->push(Marca::factory()->create(['nombre' => 'Vans', 'slug' => 'vans']));

        // 4. Crear Tiendas Reales (Donde compararemos precios)
        $tiendas = collect();
        $tiendas->push(Tienda::factory()->create(['nombre' => 'Zalando', 'url_base' => 'https://zalando.es']));
        $tiendas->push(Tienda::factory()->create(['nombre' => 'Amazon', 'url_base' => 'https://amazon.es']));
        $tiendas->push(Tienda::factory()->create(['nombre' => 'El Corte Inglés', 'url_base' => 'https://elcorteingles.es']));
        $tiendas->push(Tienda::factory()->create(['nombre' => 'Web Oficial', 'url_base' => 'https://nike.com']));

        // 5. GENERACIÓN DE PRODUCTOS Y PRECIOS (La parte clave)
        // Vamos a crear 50 productos aleatorios
        Producto::factory(50)->make()->each(function ($producto) use ($marcas, $catZapatillas, $catSudaderas, $tiendas) {
            
            // Asignar marca y categoría aleatoria de las que creamos arriba
            $producto->marca_id = $marcas->random()->id;
            $producto->categoria_id = rand(0, 1) ? $catZapatillas->id : $catSudaderas->id;
            $producto->save();

            // AHORA VIENE LO IMPORTANTE: Asignar precios en varias tiendas
            // Seleccionamos entre 2 y 4 tiendas al azar para este producto
            $tiendasRandom = $tiendas->random(rand(2, 4));

            foreach ($tiendasRandom as $tienda) {
                // Generamos un precio base y le variamos un poco para cada tienda
                $precioBase = rand(50, 150);
                $variacion = rand(-10, 10); // Variación de precio entre tiendas
                
                $producto->tiendas()->attach($tienda->id, [
                    'precio' => $precioBase + $variacion,
                    'url' => $tienda->url_base . '/producto/' . $producto->slug,
                    'en_stock' => (bool)rand(0, 1)
                ]);
            }
        });

        $this->call([\Database\Seeders\SeederHistorialPrecio::class]);

        echo "✅ Base de datos poblada con éxito: 50 productos con múltiples precios.\n";
    }
}