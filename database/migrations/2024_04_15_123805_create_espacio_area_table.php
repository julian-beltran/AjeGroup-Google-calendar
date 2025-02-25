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
        Schema::create('espacio_area', function (Blueprint $table) {
            $table->id();
            //foranea de espacios
            $table->foreignId('id_espacio')
                ->nullable()
                ->constrained('espacios')
                ->onUpdate('cascade')
                ->onDelete('set null');
            //foranea de areas
            $table->foreignId('id_area')
                ->nullable()
                ->constrained('areas')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espacio_area');
    }
};
