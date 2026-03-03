<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Marca;
use App\Models\Categoria;
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

        // 4. GENERACIÓN DE PRODUCTOS
        // Vamos a crear 50 productos aleatorios
        Producto::factory(50)->make()->each(function ($producto) use ($marcas, $catZapatillas, $catSudaderas) {
            
            // Asignar marca y categoría aleatoria de las que creamos arriba
            $producto->marca_id = $marcas->random()->id;
            $producto->categoria_id = rand(0, 1) ? $catZapatillas->id : $catSudaderas->id;
            $producto->save();
        });

        echo "✅ Base de datos poblada con éxito: 50 productos.\n";
    }
}