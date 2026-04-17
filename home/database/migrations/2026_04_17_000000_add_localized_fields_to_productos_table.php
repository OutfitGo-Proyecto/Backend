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
        Schema::table('productos', function (Blueprint $table) {
            $table->string('nombre_en')->nullable()->after('nombre');
            $table->string('nombre_fr')->nullable()->after('nombre_en');
            $table->text('descripcion_en')->nullable()->after('descripcion');
            $table->text('descripcion_fr')->nullable()->after('descripcion_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['nombre_en', 'nombre_fr', 'descripcion_en', 'descripcion_fr']);
        });
    }
};
