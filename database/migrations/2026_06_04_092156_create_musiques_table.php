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
        Schema::create('musiques', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('numero_musique');
            $table->unsignedBigInteger('album_id');
            $table->foreign('album_id')
                ->references('id')
                ->on('albums')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->decimal('prix', 5, 2);
            $table->integer('duree');

            $table->timestamps();

            $table->unique(['album_id', 'numero_musique']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musiques');
    }
};
