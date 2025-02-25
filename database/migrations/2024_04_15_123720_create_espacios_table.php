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
        Schema::create('espacios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_corporativo')
                ->nullable()
                ->constrained('corporativos')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->string('nombre');
            $table->string('descripcion');
            $table->json('config');
            $table->string('tipo_reunion');
            $table->integer('frecuencia');
            $table->string('adjunto')->nullable();
            $table->text('guia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espacios');
    }
};
