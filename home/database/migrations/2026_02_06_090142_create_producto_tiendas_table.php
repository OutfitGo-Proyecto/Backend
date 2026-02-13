<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('producto_tiendas', function (Blueprint $table) {
        $table->id();
        
        // Relaciones
        $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
        $table->foreignId('tienda_id')->constrained('tiendas')->cascadeOnDelete();
        
        // Datos de la oferta
        $table->decimal('precio', 10, 2); 
        $table->string('url', 2048); 
        $table->boolean('en_stock')->default(true); // in_stock -> en_stock
        
        $table->timestamps();
        
        // Evitar duplicados
        $table->unique(['producto_id', 'tienda_id']);
    });
}
};
