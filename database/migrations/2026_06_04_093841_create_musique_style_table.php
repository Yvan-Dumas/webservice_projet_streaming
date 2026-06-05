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
        Schema::create('musique_style', function (Blueprint $table) {
            $table->unsignedBigInteger('musique_id');
            $table->foreign('musique_id')
                ->references('id')
                ->on('musiques')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('style_id');
            $table->foreign('style_id')
                ->references('id')
                ->on('styles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();

            $table->primary(['musique_id', 'style_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musique_style');
    }
};
