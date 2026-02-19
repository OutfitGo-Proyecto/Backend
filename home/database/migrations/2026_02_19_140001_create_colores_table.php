<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // 'Rojo', 'Azul'
            $table->string('hex_code')->nullable(); // '#FF0000'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colores');
    }
};
