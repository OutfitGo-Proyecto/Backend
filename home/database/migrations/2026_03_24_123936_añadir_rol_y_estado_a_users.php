<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // El rol por defecto será 'cliente'
            $table->string('rol')->default('cliente');
            // true = puede entrar, false = baneado/suspendido
            $table->boolean('is_active')->default(true); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rol', 'is_active']);
        });
    }
};
