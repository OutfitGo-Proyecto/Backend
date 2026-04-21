<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Color;
use App\Models\Talla;
use App\Models\ProductoVariante;
use Illuminate\Support\Str;

class OutfitTestSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $colores = Color::all();
        
        $tallasCalzado = Talla::where('nombre', 'like', '4%')->get(); 
        $tallasRopa = Talla::whereIn('nombre', ['XS','S', 'M', 'L', 'XL', 'XXL'])->get();

        if ($marcas->isEmpty() || $categorias->isEmpty() || $colores->isEmpty()) {
            echo "❌ Faltan datos base en la DB. Ejecuta tus otros seeders primero.\n";
            return;
        }

        // FORMATO: ['Nombre', 'Categoría BD', 'Keyword Genérica (para la foto)', 'Descripción']
        $items = [
            // --- 20 CAMISETAS / TOPS ---
            ['Camiseta Básica de Algodón', 'Camisetas', 'tshirt', 'Manga corta transpirable, ideal para un look casual en verano.'],
            ['Camiseta Oversize Estampada', 'Camisetas', 'tshirt', 'Corte holgado y estilo urbano con gráfico moderno.'],
            ['Polo Piqué Clásico', 'Camisetas', 'polo', 'Prenda elegante con cuello, perfecta para la oficina o jugar al golf.'],
            ['Blusa de Lino Fluida', 'Camisetas', 'blouse', 'Blusa muy fresca y ligera, ideal para climas cálidos y playa.'],
            ['Camisa Oxford de Botones', 'Camisetas', 'shirt', 'Camisa formal indispensable para trajes o reuniones de trabajo.'],
            ['Camisa de Franela Leñador', 'Camisetas', 'shirt', 'Camisa gruesa a cuadros, perfecta para abrigar en pleno otoño.'],
            ['Top Deportivo Transpirable', 'Camisetas', 'tshirt', 'Ajustado para gimnasio o salir a correr, absorbe el sudor al instante.'],
            ['Top Crop Asimétrico', 'Camisetas', 'tshirt', 'Prenda moderna de fiesta que deja un hombro al descubierto, muy atrevido.'],
            ['Camiseta de Tirantes Ribbed', 'Camisetas', 'tshirt', 'Camiseta sin mangas de canalé, ajustada y muy veraniega para la piscina.'],
            ['Blusa de Seda Cruzada', 'Camisetas', 'blouse', 'Blusa de noche muy elegante, sedosa al tacto y sofisticada.'],
            ['Camiseta Térmica Interior', 'Camisetas', 'tshirt', 'Ajustada para llevar debajo de la ropa en nieve o frío extremo.'],
            ['Camisa Vaquera Slim Fit', 'Camisetas', 'shirt', 'Camisa de tejido jean fino, un clásico atemporal de entretiempo.'],
            ['Polo Deportivo Técnico', 'Camisetas', 'polo', 'Polo de secado rápido, la mejor opción para practicar tenis o pádel.'],
            ['Camiseta Cuello en V', 'Camisetas', 'tshirt', 'Básica con cuello de pico que estiliza la figura y aporta frescura.'],
            ['Cuerpo Body Ajustado', 'Camisetas', 'tshirt', 'Body elástico que queda perfectamente liso bajo el pantalón de vestir.'],
            ['Top Lentejuelas Fiesta', 'Camisetas', 'blouse', 'Prenda superior brillante para deslumbrar en discotecas y galas.'],
            ['Camisa Manga Corta Estampada', 'Camisetas', 'shirt', 'Camisa estilo hawaiana para vacaciones relajadas en la costa.'],
            ['Camiseta Running Reflectante', 'Camisetas', 'tshirt', 'Para salir a correr de noche con total seguridad en la ciudad.'],
            ['Top de Encaje Romántico', 'Camisetas', 'blouse', 'Prenda superior delicada para citas románticas o cenas elegantes.'],
            ['Camisa Cuello Mao Lino', 'Camisetas', 'shirt', 'Camisa sin solapas, máxima elegancia veraniega y de estilo bohemio.'],

            // --- 20 SUDADERAS Y CHAQUETAS (ABRIGOS) ---
            ['Sudadera Canguro con Capucha', 'Sudaderas', 'hoodie', 'Sudadera clásica con bolsillo frontal, estilo callejero y cómodo.'],
            ['Jersey de Punto Grueso', 'Sudaderas', 'sweater', 'Prenda de invierno muy cálida con patrón de ochos tradicionales.'],
            ['Jersey Cuello Alto Cisne', 'Sudaderas', 'sweater', 'Elegante jersey ajustado que protege el cuello del viento gélido.'],
            ['Sudadera Cuello Redondo Retro', 'Sudaderas', 'sweater', 'Sudadera sin capucha con efecto lavado vintage de los 90s.'],
            ['Chaqueta Vaquera Clásica', 'Chaquetas', 'jacket', 'Prenda exterior todoterreno para primavera y otoño.'],
            ['Cazadora Biker de Cuero', 'Chaquetas', 'jacket', 'Chaqueta motera resistente al viento, aporta un estilo muy rebelde.'],
            ['Blazer Americana Sastre', 'Chaquetas', 'jacket', 'Chaqueta estructurada de traje, imprescindible para negocios y bodas.'],
            ['Gabardina Trench Larga', 'Chaquetas', 'coat', 'Abrigo clásico repelente al agua para días de lluvia y niebla.'],
            ['Abrigo Largo de Paño', 'Chaquetas', 'coat', 'La opción de máxima elegancia para combatir el frío invernal.'],
            ['Chaqueta Cortavientos Running', 'Chaquetas', 'jacket', 'Capa exterior súper ligera para hacer deporte con fuertes rachas de viento.'],
            ['Plumífero Acolchado Nieve', 'Chaquetas', 'coat', 'Chaqueta rellena de plumas, aísla contra temperaturas bajo cero y nevadas.'],
            ['Chaleco Acolchado', 'Chaquetas', 'jacket', 'Prenda sin mangas para mantener el pecho caliente con libertad de movimiento.'],
            ['Cárdigan de Punto Abierto', 'Chaquetas', 'sweater', 'Chaqueta de lana con botones, muy cómoda y de estilo casero.'],
            ['Sobrecamisa de Pana', 'Chaquetas', 'jacket', 'Punto medio entre camisa y chaqueta, tela de pana muy cálida.'],
            ['Chaqueta Bomber Aviador', 'Chaquetas', 'jacket', 'Chaqueta corta y abullonada, un icono urbano de la moda retro.'],
            ['Poncho Capa de Invierno', 'Chaquetas', 'sweater', 'Alternativa al abrigo, capa ancha y extremadamente cómoda para el frío.'],
            ['Cortavientos Impermeable', 'Chaquetas', 'coat', 'Chubasquero con costuras selladas contra tormentas tropicales.'],
            ['Sudadera Polar Media Cremallera', 'Sudaderas', 'sweater', 'Forro polar técnico ideal para montañismo o acampadas con mucho frío.'],
            ['Chaqueta Terciopelo Noche', 'Chaquetas', 'jacket', 'Blazer de textura rica y brillante para galas y eventos nocturnos top.'],
            ['Cazadora Harrington', 'Chaquetas', 'jacket', 'Chaqueta ligera de entretiempo con cuello alto y cierre de botones.'],

            // --- 20 PANTALONES (BOTTOMS) ---
            ['Vaqueros Rectos Straight', 'Pantalones', 'jeans', 'El corte clásico del denim, no pasa de moda, aguanta perfectamente el frío.'],
            ['Vaqueros Skinny Elásticos', 'Pantalones', 'jeans', 'Pantalones muy ajustados que se adaptan a la silueta de la pierna.'],
            ['Vaqueros Campana Vintage', 'Pantalones', 'jeans', 'Pantalón ajustado arriba y muy ancho abajo, puramente estilo años 70.'],
            ['Vaqueros Mom Fit Rotos', 'Pantalones', 'jeans', 'Tiro alto y corte holgado con rasgaduras en las rodillas estilo rebelde.'],
            ['Pantalón Chino de Vestir', 'Pantalones', 'pants', 'Pantalón de tela casual pero formal, el estándar moderno de oficina.'],
            ['Pantalón Sastre de Pinzas', 'Pantalones', 'pants', 'Pantalón formal con raya marcada, para bodas, bautizos o reuniones.'],
            ['Pantalón Cargo Multibolsillos', 'Pantalones', 'pants', 'Estilo militar holgado, con bolsillos laterales súper prácticos y resistentes.'],
            ['Pantalón de Chándal Jogger', 'Pantalones', 'pants', 'Pantalón deportivo de algodón suave con elástico en los tobillos.'],
            ['Pantalón Fluido de Lino', 'Pantalones', 'pants', 'Pantalón muy ancho, la mejor opción para no pasar nada de calor en verano.'],
            ['Leggings Deportivos Compresión', 'Pantalones', 'pants', 'Mallas técnicas para yoga, crossfit o correr largas distancias.'],
            ['Pantalón Culotte Ancho', 'Pantalones', 'pants', 'Pantalón de pernera muy ancha que corta elegantemente por encima del tobillo.'],
            ['Pantalón de Cuero Sintético', 'Pantalones', 'pants', 'Pantalón ajustado y brillante, la prenda estrella para salir de noche.'],
            ['Bermudas Vaqueras Cortas', 'Pantalones', 'shorts', 'Pantalón corto de jean, el básico indispensable de cualquier verano.'],
            ['Pantalón Corto Chino', 'Pantalones', 'shorts', 'Bermuda de tela elegante para looks frescos pero arreglados en la costa.'],
            ['Shorts Deportivos Ligeros', 'Pantalones', 'shorts', 'Pantalón muy corto y transpirable diseñado específicamente para el gimnasio.'],
            ['Pantalón de Pana Invierno', 'Pantalones', 'pants', 'Tejido grueso con surcos, retiene muchísimo el calor corporal en la nieve.'],
            ['Pantalón Campana Estampado', 'Pantalones', 'pants', 'Pantalón bohemio con patrones llamativos, ideal para festivales de música.'],
            ['Mallas Térmicas Nieve', 'Pantalones', 'pants', 'Pantalón interior de primera capa para esquiar o soportar días gélidos.'],
            ['Bermuda Cargo Aventura', 'Pantalones', 'shorts', 'Pantalón corto con grandes bolsillos para excursiones o senderismo en la montaña.'],
            ['Pantalón Palazzo Elegante', 'Pantalones', 'pants', 'Pantalón larguísimo y ancho, vuela al caminar, extremadamente formal para galas.'],

            // --- 20 VESTIDOS Y FALDAS ---
            ['Vestido Camisero Casual', 'Vestidos', 'dress', 'Vestido con botones y cuello de camisa, ideal para ir a trabajar fresca.'],
            ['Vestido Largo Boho Flores', 'Vestidos', 'dress', 'Vestido primaveral vaporoso que llega hasta los pies, muy romántico.'],
            ['Vestido de Noche Lentejuelas', 'Vestidos', 'dress', 'Prenda corta y brillante diseñada para ser el centro de atención en la fiesta.'],
            ['Vestido Punto Acanalado', 'Vestidos', 'dress', 'Vestido de invierno muy ajustado que te abriga tanto como un jersey grueso.'],
            ['Vestido Lencero de Seda', 'Vestidos', 'dress', 'Vestido de tirantes finos, muy sofisticado, ideal para eventos nocturnos.'],
            ['Vestido Cruzado Wrap', 'Vestidos', 'dress', 'Vestido que se ata a la cintura, favorece a cualquier silueta y es comodísimo.'],
            ['Mono Largo Elegante', 'Vestidos', 'dress', 'Jumpsuit de una pieza formal, la alternativa moderna y elegante al traje.'],
            ['Mono Corto Verano', 'Vestidos', 'dress', 'Mono de una pieza con pantalón corto, la opción más rápida y cómoda para el calor.'],
            ['Peto Vaquero Clásico', 'Vestidos', 'dress', 'Peto completo de denim con tirantes, estilo retro y muy casual para el día a día.'],
            ['Vestido Playa Ganchillo', 'Vestidos', 'dress', 'Vestido semi-transparente ideal para llevar puesto por encima del bañador.'],
            ['Falda Vaquera Midi', 'Vestidos', 'skirt', 'Falda de jean que llega justo a la rodilla con una elegante apertura frontal.'],
            ['Minifalda de Cuero', 'Vestidos', 'skirt', 'Falda muy corta y ajustada de estilo rockero para salir de noche.'],
            ['Falda Larga Plisada', 'Vestidos', 'skirt', 'Falda elegante con cientos de dobleces, perfecta para bodas o eventos formales.'],
            ['Falda Tubo Oficina', 'Vestidos', 'skirt', 'Falda formal que se ajusta perfectamente al cuerpo, muy profesional y seria.'],
            ['Falda Pantalón Deportiva', 'Vestidos', 'skirt', 'Falda de tenis con un pequeño pantalón corto debajo, para jugar con seguridad.'],
            ['Falda Pareo Asimétrica', 'Vestidos', 'skirt', 'Falda cruzada que se ata a un lado, ideal para looks relajados de puro verano.'],
            ['Vestido Midi Ajustado', 'Vestidos', 'dress', 'Vestido de corte por la rodilla que marca la figura de forma espectacular.'],
            ['Túnica de Lino', 'Vestidos', 'dress', 'Vestido suelto, holgado y extremadamente fresco para no sudar nada.'],
            ['Falda Tul Princesa', 'Vestidos', 'skirt', 'Falda voluminosa y mágica para eventos muy especiales, graduaciones o bodas.'],
            ['Vestido Blazer', 'Vestidos', 'dress', 'Americana larga que hace la función de vestido, empoderador, corto y muy formal.'],

            // --- 15 CALZADO ---
            ['Zapatillas Deportivas Running', 'Zapatillas', 'sneakers', 'Zapatillas con gran amortiguación para correr maratones o caminar sin cansarse.'],
            ['Zapatillas Lona Blancas', 'Zapatillas', 'sneakers', 'Zapatillas planas clásicas, combinan a la perfección con cualquier ropa de verano.'],
            ['Zapatillas Skate Anchas', 'Zapatillas', 'sneakers', 'Calzado urbano muy resistente de suela ancha, soporta el desgaste extremo.'],
            ['Zapatillas Altas Retro', 'Zapatillas', 'sneakers', 'Zapatillas estilo baloncesto de los 80 que cubren y protegen el tobillo.'],
            ['Zapatos Oxford Formales', 'Zapatillas', 'shoes', 'Calzado de cuero brillante con cordones para trajes de novio, galas o la oficina.'],
            ['Mocasines Piel Borlas', 'Zapatillas', 'shoes', 'Zapatos muy elegantes sin cordones, estilo preppy para entornos de lujo.'],
            ['Botines Chelsea Cuero', 'Zapatillas', 'boots', 'Botas cortas con un elástico lateral, muy versátiles y fáciles de poner en otoño.'],
            ['Botas Militares Cordones', 'Zapatillas', 'boots', 'Botas resistentes de cuero duro de caña alta, aguantan lluvia, nieve y barro.'],
            ['Botas Nieve Impermeables', 'Zapatillas', 'boots', 'Botas forradas de pelo sintético por dentro para soportar temperaturas extremas.'],
            ['Zapatos Salón Tacón Aguja', 'Zapatillas', 'shoes', 'Tacones altos clásicos para la máxima elegancia nocturna en alfombras rojas.'],
            ['Sandalias Tiras Tacón', 'Zapatillas', 'sandals', 'Calzado de fiesta abierto para dejar respirar el pie en eventos de puro verano.'],
            ['Sandalias Planas Cuero', 'Zapatillas', 'sandals', 'Calzado abierto comodísimo para pasear por la playa o la ciudad con calor.'],
            ['Alpargatas Yute Cuña', 'Zapatillas', 'shoes', 'Zapatos mediterráneos de tela y esparto, el calzado definitivo para las vacaciones.'],
            ['Zuecos de Piel', 'Zapatillas', 'shoes', 'Calzado abierto por el talón con suela de madera, muy rápidos de poner.'],
            ['Botas Altas Mosqueteras', 'Zapatillas', 'boots', 'Botas de ante que llegan por encima de la rodilla, espectaculares para el invierno.'],

            // --- 5 ACCESORIOS ---
            ['Gorra Béisbol Algodón', 'Accesorios', 'cap', 'Gorra con visera clásica para proteger la cara del sol abrasador en agosto.'],
            ['Gorro Punto Invierno', 'Accesorios', 'hat', 'Gorro de lana grueso que cubre las orejas contra el viento helado.'],
            ['Sombrero Paja Ala Ancha', 'Accesorios', 'hat', 'Sombrero de playa clásico, aporta mucha sombra al rostro y muchísimo estilo bohemio.'],
            ['Bufanda Lana Gruesa', 'Accesorios', 'scarf', 'Accesorio de invierno muy largo para dar varias vueltas y abrigar bien el cuello.'],
            ['Gafas Sol Polarizadas', 'Accesorios', 'sunglasses', 'Protección ocular imprescindible con cristales oscuros para días excesivamente luminosos.']
        ];

        $contador = 0;

        foreach ($items as $index => $item) {
            
            // 1. Asignamos Categoría y Tallas
            $categoriaDb = $categorias->where('nombre', $item[1])->first() ?? $categorias->first();
            
            if ($categoriaDb->slug == 'zapatillas') {
                $tallasUsar = $tallasCalzado;
            } elseif ($categoriaDb->slug == 'accesorios') {
                $tallasUsar = Talla::where('nombre', 'TU')->get();
                if ($tallasUsar->isEmpty()) $tallasUsar = Talla::where('nombre', 'M')->get();
            } else {
                $tallasUsar = $tallasRopa;
            }

            // 2. Elegimos un color aleatorio para cada producto
            $colorAleatorio = $colores->random(); 
            
            // 3. LA URL MAGNÍFICA Y ARREGLADA
            // Usamos la palabra genérica (ej: 'sneakers', 'jeans') y quitamos el 'fashion,'
            $keyword = $item[2];
            $urlFoto = "https://loremflickr.com/800/800/{$keyword}?lock={$index}";

            // 4. Idempotencia: Crea el producto solo si no existe el nombre
            $producto = Producto::firstOrCreate(
                ['nombre' => $item[0]], 
                [
                    'slug' => Str::slug($item[0]),
                    'descripcion' => $item[3],
                    'publico' => rand(0, 1) ? 'hombre' : 'mujer',
                    'url_imagen_principal' => $urlFoto, 
                    'precio' => rand(15, 99) + 0.99,
                    'stock' => rand(20, 100),
                    'marca_id' => $marcas->random()->id,
                    'categoria_id' => $categoriaDb->id,
                ]
            );

            // 5. Variantes y stock (solo si es nuevo)
            if ($producto->wasRecentlyCreated) {
                $producto->colores()->sync([$colorAleatorio->id]);
                $producto->tallas()->sync($tallasUsar->pluck('id'));

                foreach ($tallasUsar as $talla) {
                    ProductoVariante::create([
                        'producto_id' => $producto->id,
                        'talla_id' => $talla->id,
                        'color_id' => $colorAleatorio->id,
                        'stock' => rand(5, 20)
                    ]);
                }
                $contador++;
            }
        }

        echo "\n✅ BASE DE DATOS MASIVA: $contador productos inyectados con éxito. ¡Listo para probar el Outfit Wizard!\n";
    }
}