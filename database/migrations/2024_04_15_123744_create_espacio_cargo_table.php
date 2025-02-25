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
        Schema::create('espacio_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cargo')
                ->nullable()
                ->constrained('cargos')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreignId('id_espacio')
                ->nullable()
                ->constrained('espacios')
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
        Schema::dropIfExists('espacio_cargo');
    }
};
