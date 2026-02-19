<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tallas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // 'S', 'M', 'L', '40', '42'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tallas');
    }
};
