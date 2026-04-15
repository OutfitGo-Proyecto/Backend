<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('producto_variante_id')->after('user_id')->nullable()->constrained('producto_variantes')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('producto_variante_id')->after('order_id')->nullable()->constrained('producto_variantes')->nullOnDelete();
            $table->foreignId('producto_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['producto_variante_id']);
            $table->dropColumn('producto_variante_id');
            $table->foreignId('producto_id')->nullable(false)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['producto_variante_id']);
            $table->dropColumn('producto_variante_id');
            $table->foreignId('producto_id')->nullable(false)->change();
        });
    }
};
