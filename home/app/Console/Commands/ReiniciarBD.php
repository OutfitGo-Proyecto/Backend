<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReiniciarBD extends Command
{
    protected $signature = 'reiniciar';
    protected $description = 'Borra la base de datos entera, la recrea y le mete los datos de prueba';

    public function handle()
    {
        // Te avisa (en color rojo/amarillo) por si le has dado sin querer
        if ($this->confirm('⚠️ ¿Seguro que quieres borrar TODA la base de datos y recrearla?')) {
            $this->info('💥 Destruyendo y reconstruyendo...');
            
            // Llama al comando original y le pasa el --seed
            $this->call('migrate:fresh', ['--seed' => true]);
            
            $this->info('✅ ¡Base de datos como nueva y llena de datos!');
        } else {
            $this->info('😅 Operación cancelada. ¡Menos mal!');
        }
    }
}
    