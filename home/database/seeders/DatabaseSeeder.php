<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Talla;
use App\Models\Color;
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

        // 4. Crear Tallas y Colores Base
        $tallasAdulto = collect();
        $tallasAdulto->push(Talla::create(['nombre' => 'S']));
        $tallasAdulto->push(Talla::create(['nombre' => 'M']));
        $tallasAdulto->push(Talla::create(['nombre' => 'L']));
        $tallasAdulto->push(Talla::create(['nombre' => 'XL']));

        $tallasInfantil = collect();
        $tallasInfantil->push(Talla::create(['nombre' => '4Y']));
        $tallasInfantil->push(Talla::create(['nombre' => '6Y']));
        $tallasInfantil->push(Talla::create(['nombre' => '8Y']));
        $tallasInfantil->push(Talla::create(['nombre' => '10Y']));

        $tallasCalzado = collect();
        $tallasCalzado->push(Talla::create(['nombre' => '38']));
        $tallasCalzado->push(Talla::create(['nombre' => '40']));
        $tallasCalzado->push(Talla::create(['nombre' => '42']));
        $tallasCalzado->push(Talla::create(['nombre' => '44']));

        $colores = collect();
        $colores->push(Color::create(['nombre' => 'Negro', 'hex_code' => '#000000']));
        $colores->push(Color::create(['nombre' => 'Blanco', 'hex_code' => '#FFFFFF']));
        $colores->push(Color::create(['nombre' => 'Azul', 'hex_code' => '#0000FF']));
        $colores->push(Color::create(['nombre' => 'Rojo', 'hex_code' => '#FF0000']));

        // 5. GENERACIÓN DE PRODUCTOS
        // Vamos a crear 50 productos aleatorios
        Producto::factory(50)->make()->each(function ($producto) use (
            $marcas, $catZapatillas, $catSudaderas, 
            $tallasAdulto, $tallasInfantil, $tallasCalzado, $colores
        ) {
            
            // Asignar marca y categoría aleatoria de las que creamos arriba
            $producto->marca_id = $marcas->random()->id;
            $producto->categoria_id = rand(0, 1) ? $catZapatillas->id : $catSudaderas->id;
            $producto->save();

            // Asignar Tallas coherentemente
            $tallasAAsignar = collect();
            if ($producto->categoria_id === $catZapatillas->id) {
                // Si es calzado
                $tallasAAsignar = $tallasCalzado->random(rand(1, 3));
            } else {
                // Si es ropa (sudadera) depende del público
                if ($producto->publico === 'infantil') {
                    $tallasAAsignar = $tallasInfantil->random(rand(1, 3));
                } else {
                    $tallasAAsignar = $tallasAdulto->random(rand(1, 3));
                }
            }

            // Guardar tallas en pivote
            $producto->tallas()->attach($tallasAAsignar->pluck('id')->toArray());

            // Asignar Colores (de 1 a 2 colores)
            $coloresRandom = $colores->random(rand(1, 2));
            $producto->colores()->attach($coloresRandom->pluck('id')->toArray());
        });

        echo "✅ Base de datos poblada con éxito: 50 productos.\n";
    }
}