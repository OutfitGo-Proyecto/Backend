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
    Schema::create('marcas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // name -> nombre
        $table->string('slug')->unique(); 
        $table->string('url_logo')->nullable(); // logo_url -> url_logo
        $table->timestamps();
    });
}
};
