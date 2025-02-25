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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            //ID del usuario AUTHENTICADO QUE GENERA LA AGENDA:
            $table->bigInteger('id_user');
            $table->bigInteger('id_corporativo');
            $table->bigInteger('id_espacio');
            $table->bigInteger('id_area')->nullable(); //para poder integrar solo agendas individuales
            $table->dateTime('fecha_hora_meet');
            $table->dateTime('fecha_hora_termino');
            //add
            $table->enum('estado', ['terminado', 'pendiente'])->default('pendiente');

            /* Para vincular calendar a base de datos para edit y delete------------------------------------------------------------ */
            $table->string('summary')->nullable();
            $table->string('location')->nullable();
            $table->string('event_google_id')->nullable();
            $table->string('hangoutLink')->nullable(); // meet
            $table->string('htmlLink')->nullable(); // calendar
            /* Para vincular calendar a base de datos para edit y delete------------------------------------------------------------ */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
