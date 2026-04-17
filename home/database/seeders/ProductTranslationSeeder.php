<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Traducciones para Categorías
        $categories = [
            'HOMBRE' => ['en' => 'Men', 'fr' => 'Homme'],
            'MUJER' => ['en' => 'Women', 'fr' => 'Femme'],
            'INFANTIL' => ['en' => 'Kids', 'fr' => 'Enfants'],
        ];

        foreach ($categories as $es => $langs) {
            $cat = Categoria::where('nombre', $es)->first();
            if ($cat) {
                $cat->update([
                    'nombre_en' => $langs['en'],
                    'nombre_fr' => $langs['fr'],
                ]);
            }
        }

        // Traducciones para algunos productos de ejemplo
        $products = Producto::all();

        foreach ($products as $product) {
            // Ejemplo genérico si no tiene traducción específica
            if (!$product->nombre_en) {
                $product->update([
                    'nombre_en' => $product->nombre . ' (EN)',
                    'nombre_fr' => $product->nombre . ' (FR)',
                    'descripcion_en' => 'English description for ' . $product->nombre . '. High quality and style.',
                    'descripcion_fr' => 'Description en français pour ' . $product->nombre . '. Haute qualité et style.',
                ]);
            }
        }

        $this->command->info('Traducciones de productos y categorías aplicadas con éxito.');
    }
}
