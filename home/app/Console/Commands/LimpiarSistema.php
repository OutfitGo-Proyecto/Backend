<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LimpiarSistema extends Command
{
    // 1. Aquí defines CÓMO quieres llamar al comando en la terminal
    protected $signature = 'limpiar';

    // 2. Una pequeña descripción para cuando pongas "php artisan list"
    protected $description = 'Atajo para limpiar toda la caché y vistas de Laravel';

    // 3. Lo que se ejecuta cuando escribes "php artisan limpiar"
    public function handle()
    {
        $this->info('Iniciando limpieza profunda... 🧹');

        // Llamamos al comando original por debajo
        $this->call('optimize:clear');

        // (Opcional) ¡Podrías añadir más comandos aquí si quisieras!
        // $this->call('view:clear');

        $this->info('¡Listo! Sistema limpio como una patena. ✨');
    }
}