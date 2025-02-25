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
        Schema::create('agenda_archivos', function (Blueprint $table) {
            $table->id();
            $table->json('archivos')->nullable();
            // $table->string('url_archivo');
            //foranea de agenda -> agenda ----> agenda_archivo (uno a uno)
            $table->foreignId('id_agenda')
                ->nullable()
                ->constrained('agendas')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->bigInteger('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_archivos');
    }
};
