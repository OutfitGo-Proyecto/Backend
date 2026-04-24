<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resenas_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer('puntuacion'); // Ej: 1 a 5
            $table->text('comentario');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resenas_productos');
    }
};
