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
        Schema::create('area_user', function (Blueprint $table) {
            $table->id();
            //foranea de area
            $table->foreignId('id_area')
                ->nullable()
                ->constrained('areas')
                ->onUpdate('cascade')
                ->onDelete('set null');
            //foranea de users
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
        Schema::dropIfExists('area_user');
    }
};
