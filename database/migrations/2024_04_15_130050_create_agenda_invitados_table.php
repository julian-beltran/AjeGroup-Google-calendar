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
        Schema::create('agenda_invitados', function (Blueprint $table) {
            $table->id();
            //foranea de agenda:
            $table->foreignId('id_agenda')
                ->nullable()
                ->constrained('agendas')
                ->onUpdate('cascade')
                ->onDelete('set null');
            //foranea de usuarios
            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            //Foránea de
            $table->bigInteger('id_area')->nullable(); //para agendar usuarios grupales -> xsilas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_invitados');
    }
};
