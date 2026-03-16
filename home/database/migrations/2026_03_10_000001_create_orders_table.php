<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->decimal('total', 10, 2);
        
        $table->string('estado')->default('pendiente'); 

        // --- DATOS DE ENVÍO ---
        $table->string('nombre');
        $table->string('apellidos');
        $table->string('telefono'); 
        $table->string('direccion'); 
        $table->string('ciudad');
        $table->string('provincia');
        $table->string('codigo_postal');
        $table->text('notas')->nullable(); 

        $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
