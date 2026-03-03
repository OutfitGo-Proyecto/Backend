<?php

namespace Database\Factories;
use App\Models\Marca;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(3), 
            'slug' => $this->faker->unique()->slug(),
            'descripcion' => $this->faker->paragraph(),
            'url_imagen_principal' => $this->faker->imageUrl(640, 480, 'fashion', true, 'Product'),
            'precio' => $this->faker->randomFloat(2, 10, 200),
            'stock' => $this->faker->numberBetween(0, 50),
            
            'marca_id' => Marca::factory(),
            'categoria_id' => Categoria::factory(),
        ];
    }
}
