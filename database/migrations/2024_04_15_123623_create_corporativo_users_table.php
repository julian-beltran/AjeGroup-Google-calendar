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
        Schema::create('corporativo_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_corporativo')
                ->nullable()
                ->constrained('corporativos')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users')
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
        Schema::dropIfExists('corporativo_users');
    }
};
