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
        Schema::create('excel', function (Blueprint $table) {
            $table->id('id');
            $table->string('centro');
            $table->string('almacen');
            $table->bigInteger('material');
            $table->string('texto_breve_de_material');
            $table->string('grupo_de_articulos');
            $table->string('lote');
            $table->string('unidad_de_medida');
            $table->integer('libre_utilizacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel');
    }
};






