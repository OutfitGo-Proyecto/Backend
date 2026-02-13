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
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        
        // Claves foráneas en español
        // Laravel buscará la tabla 'marcas' y 'categorias' automáticamente
        // si el modelo se llama Marca y Categoria, pero aquí forzamos la relación:
        $table->foreignId('marca_id')->constrained('marcas')->cascadeOnDelete();
        $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
        
        $table->string('nombre');
        $table->string('slug')->unique();
        $table->text('descripcion')->nullable();
        $table->string('url_imagen_principal')->nullable(); // main_image_url
        $table->timestamps();
    });
}
};
