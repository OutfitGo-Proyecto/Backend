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
    Schema::create('tiendas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('url_base'); // base_url -> url_base
        $table->string('url_logo')->nullable();
        $table->timestamps();
    });
}
};
